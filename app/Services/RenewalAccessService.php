<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Issues revocable, hashed login tokens for renewal emails.
 * Does not read or write user passwords. Plaintext token exists only in the email URL.
 */
class RenewalAccessService
{
    public const DEFAULT_TTL_DAYS = 30;

    /**
     * Rotate token on the reminder and return the absolute access URL for the email CTA.
     */
    public function issueAccessUrl(Reminder $reminder, Cart $cart): string
    {
        $plain = Str::random(64);
        $ttlDays = (int) config('renewal.access_token_ttl_days', self::DEFAULT_TTL_DAYS);

        $reminder->access_token_hash = hash('sha256', $plain);
        $reminder->access_token_expires_at = Carbon::now()->addDays(max(1, $ttlDays));
        $reminder->pw = null;
        $reminder->save();

        return route('front.renewal.access', [
            'cart_ref' => $cart->cart_ref,
            'token' => $plain,
        ]);
    }

    /**
     * Validate token for cart owner. Returns User on success, null otherwise.
     */
    public function validate(Cart $cart, string $plainToken): ?User
    {
        if ($plainToken === '' || strlen($plainToken) < 32) {
            return null;
        }

        $reminder = Reminder::where('cart_id', $cart->id)->first();
        if (!$reminder || !$reminder->access_token_hash || !$reminder->access_token_expires_at) {
            return null;
        }

        if (Carbon::now()->greaterThan(Carbon::parse($reminder->access_token_expires_at))) {
            return null;
        }

        $given = hash('sha256', $plainToken);
        if (!hash_equals($reminder->access_token_hash, $given)) {
            return null;
        }

        $user = $cart->user;
        if (!$user || (int) $cart->user_id !== (int) $user->id) {
            return null;
        }

        return $user;
    }

    /**
     * Invalidate token after successful use is optional; for renewal campaigns
     * tokens stay valid until expiry so the customer can reopen the mail.
     * Call this when moving to one-time links (e.g. with KAN-8 OTP).
     */
    public function revoke(Reminder $reminder): void
    {
        $reminder->access_token_hash = null;
        $reminder->access_token_expires_at = null;
        $reminder->pw = null;
        $reminder->save();
    }
}
