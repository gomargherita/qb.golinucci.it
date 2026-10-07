<?php

namespace App\Services\Payments;

use App\Models\Cart;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\Product;
use App\Notifications\FinalEmailWithAttachments;
use App\Notifications\FinalEmailWithAttachmentsNotPec;
use App\Services\PaymentBankersToPdfService;
use App\Services\PaymentCertificateToPdfService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Side-effects after a payment is marked paid (PDF, certificate, step 5).
 * Shared by browser return, webhook and reconcile.
 */
class CompletePaidCartService
{
    /**
     * @return array{ok: bool, reason: string}
     */
    public function complete(Cart $cart, Payment $payment, Product $product): array
    {
        $cart->loadMissing(['user', 'productOffer.contract', 'payment']);

        if (!$payment->paid) {
            $payment->updatePaymentStatus();
        }

        $payment->updatePaymentPaymentNo();

        try {
            if ((int) $product->product_type_id === 1) {
                (new PaymentCertificateToPdfService($cart))->generateAndPersist();
            }

            if ((int) $product->product_type_id === 2) {
                $this->completeBankersFlow($cart, $payment);
            }

            if ((int) $cart->product_step_id < 5) {
                $cart->updateCartStep(5);
            }

            return ['ok' => true, 'reason' => 'completed'];
        } catch (\Throwable $e) {
            Log::error('payment.complete_cart.failed', [
                'cart_ref' => $cart->cart_ref,
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return ['ok' => false, 'reason' => 'post_payment_failed'];
        }
    }

    private function completeBankersFlow(Cart $cart, Payment $payment): void
    {
        if (Carbon::now()->lt('31-12-2025')) {
            $startReal = Carbon::parse('31-12-2025')->format('Y-m-d');
        } else {
            $startReal = $payment->paid_at
                ? $payment->paid_at->format('Y-m-d')
                : Carbon::now()->format('Y-m-d');
        }

        if ($cart->productOffer->months == 12) {
            $endReal = Carbon::parse('01-12-2026')->endOfYear()->format('Y-m-d');
        } elseif (Carbon::parse($startReal)->addMonths($cart->productOffer->months) > Carbon::parse('01-12-2026')->endOfYear()) {
            $endReal = Carbon::parse('01-12-2026')->endOfYear()->format('Y-m-d');
        } else {
            $endReal = Carbon::parse($startReal)->addMonths($cart->productOffer->months)->format('Y-m-d');
        }

        $certificate = Certificate::firstOrCreate(
            ['cart_id' => $cart->id],
            [
                'product_id' => $cart->product_id,
                'product_offer_id' => $cart->product_offer_id,
                'contract_id' => $cart->productOffer->contract->id,
                'start_validity_date' => $startReal,
                'end_validity_date' => $endReal,
            ]
        );

        activity()
            ->causedBy($cart->user)
            ->on($certificate)
            ->event('certificate_generation')
            ->log("Completed certificate {$certificate->id} generation;");

        (new PaymentBankersToPdfService($cart))->generateAndPersist();

        if ($cart->user) {
            $cart->user->notify(new FinalEmailWithAttachments($cart));
            $cart->user->notify(new FinalEmailWithAttachmentsNotPec($cart));
        }
    }
}
