<?php

namespace App\Services\Renewals;

use App\Models\Cart;
use App\Models\Certificate;
use App\Models\Product;
use App\Models\Renewal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Ensures one ordered renewal path per user/product season:
 * resume existing draft, preserve dates, link old→new cart_ref.
 */
class RenewalCartService
{
    public const RENEWAL_WINDOW_DAYS_BEFORE = 30;
    public const RENEWAL_CART_GRACE_DAYS_AFTER_END = 30;

    public function isRenewalCart(Cart $cart): bool
    {
        return Renewal::where('new_cart_ref', $cart->cart_ref)->exists();
    }

    public function latestCertificateFor(User $user, Product $product): ?Certificate
    {
        return Certificate::query()
            ->with('cart')
            ->where('product_id', $product->id)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest('end_validity_date')
            ->latest('id')
            ->first();
    }

    public function sourceCartForCertificate(Certificate $certificate): ?Cart
    {
        return $certificate->cart ?: Cart::find($certificate->cart_id);
    }

    /**
     * Open (incomplete) renewal cart for this user+product, if any.
     */
    public function findOpenRenewalDraft(User $user, Product $product): ?Cart
    {
        return Cart::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('locked', 'N')
            ->where('product_step_id', '<', 7)
            ->whereIn('cart_ref', Renewal::query()->select('new_cart_ref'))
            ->latest('id')
            ->first();
    }

    /**
     * Any open draft cart (renewal preferred by caller).
     */
    public function findOpenDraft(User $user, Product $product): ?Cart
    {
        $renewal = $this->findOpenRenewalDraft($user, $product);
        if ($renewal) {
            return $renewal;
        }

        return Cart::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('locked', 'N')
            ->where('product_step_id', '<', 7)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest('id')
            ->first();
    }

    public function isInRenewalWindow(Certificate $certificate, ?Carbon $today = null): bool
    {
        $today = $today ?: Carbon::today();
        $end = Carbon::parse($certificate->end_validity_date)->startOfDay();
        $windowStart = $end->copy()->subDays(self::RENEWAL_WINDOW_DAYS_BEFORE);
        $windowEnd = $end->copy()->addDays(60); // align with mail:renew lookback

        return $today->gte($windowStart) && $today->lte($windowEnd);
    }

    /**
     * Start or resume renewal from a completed source cart.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function startOrResumeFromSourceCart(Product $product, Cart $sourceCart, int $userId): Cart
    {
        if ((int) $sourceCart->user_id !== (int) $userId) {
            abort(404);
        }

        $user = User::findOrFail($userId);

        $existingLink = Renewal::where('old_cart_ref', $sourceCart->cart_ref)->first();
        if ($existingLink) {
            $existingNew = Cart::where('cart_ref', $existingLink->new_cart_ref)->first();
            if ($existingNew) {
                $this->ensureContinuityDates($existingNew, $user, $product);
                return $existingNew;
            }
        }

        // One open renewal draft per user+product: resume instead of forking.
        $openDraft = $this->findOpenRenewalDraft($user, $product);
        if ($openDraft) {
            Log::info('renewal.resume_open_draft', [
                'user_id' => $userId,
                'product_id' => $product->id,
                'cart_ref' => $openDraft->cart_ref,
                'requested_old' => $sourceCart->cart_ref,
            ]);
            $this->ensureContinuityDates($openDraft, $user, $product);
            return $openDraft;
        }

        $certificate = Certificate::query()
            ->where('cart_id', $sourceCart->id)
            ->latest('id')
            ->first();

        if (!$certificate) {
            $certificate = $this->latestCertificateFor($user, $product);
        }

        if (!$certificate || !$certificate->end_validity_date) {
            abort(403, 'Missing previous certificate for renewal');
        }

        $end = Carbon::parse($certificate->end_validity_date)->startOfDay();
        $today = Carbon::today();
        if ($today->lt($end->copy()->subDays(self::RENEWAL_WINDOW_DAYS_BEFORE))) {
            abort(403, 'Renewal window not open yet');
        }

        return DB::transaction(function () use ($product, $sourceCart, $userId, $user, $certificate, $end) {
            // Re-check link inside transaction
            $link = Renewal::where('old_cart_ref', $sourceCart->cart_ref)->lockForUpdate()->first();
            if ($link) {
                $cart = Cart::where('cart_ref', $link->new_cart_ref)->first();
                if ($cart) {
                    return $cart;
                }
            }

            $renewalDate = $end->toDateString();
            $retroactivity = $certificate->retroactivity
                ? Carbon::parse($certificate->retroactivity)->toDateString()
                : null;

            $newCart = Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'product_offer_id' => $product->product_type_id == 1 ? null : $sourceCart->product_offer_id,
                'product_step_id' => 1,
                'expired_at' => $end->copy()->addDays(self::RENEWAL_CART_GRACE_DAYS_AFTER_END),
                'locked' => 'N',
                'cart_ref' => uniqid(),
                'renewal_date' => $renewalDate,
                'retroactivity' => $retroactivity,
            ]);

            Renewal::create([
                'old_cart_ref' => $sourceCart->cart_ref,
                'new_cart_ref' => $newCart->cart_ref,
            ]);

            User::where('id', $userId)->update(['new_client' => 0]);

            Log::info('renewal.created', [
                'user_id' => $userId,
                'product_id' => $product->id,
                'old_cart_ref' => $sourceCart->cart_ref,
                'new_cart_ref' => $newCart->cart_ref,
                'renewal_date' => $renewalDate,
                'retroactivity' => $retroactivity,
            ]);

            return $newCart;
        });
    }

    /**
     * Restore continuity fields if wiped or missing on a renewal/draft cart.
     */
    public function ensureContinuityDates(Cart $cart, User $user, Product $product): void
    {
        $certificate = $this->latestCertificateFor($user, $product);
        if (!$certificate) {
            return;
        }

        $dirty = false;

        if (empty($cart->renewal_date) && $certificate->end_validity_date) {
            $cart->renewal_date = Carbon::parse($certificate->end_validity_date)->toDateString();
            $dirty = true;
        }

        if (empty($cart->retroactivity) && $certificate->retroactivity) {
            $cart->retroactivity = Carbon::parse($certificate->retroactivity)->toDateString();
            $dirty = true;
        }

        if ($dirty) {
            $cart->save();
            Log::info('renewal.dates_restored', [
                'cart_ref' => $cart->cart_ref,
                'renewal_date' => $cart->renewal_date,
                'retroactivity' => $cart->retroactivity,
            ]);
        }
    }

    /**
     * Resolve entry into buy flow when no cart_ref was provided.
     */
    public function resolveBuyEntry(User $user, Product $product): array
    {
        $draft = $this->findOpenDraft($user, $product);
        if ($draft) {
            if ($this->isRenewalCart($draft)) {
                $this->ensureContinuityDates($draft, $user, $product);
            }

            return ['action' => 'resume', 'cart' => $draft];
        }

        $certificate = $this->latestCertificateFor($user, $product);
        if ($certificate && $this->isInRenewalWindow($certificate)) {
            $source = $this->sourceCartForCertificate($certificate);
            if ($source) {
                return ['action' => 'renew', 'cart' => $source];
            }
        }

        return ['action' => 'create_new', 'cart' => null];
    }
}
