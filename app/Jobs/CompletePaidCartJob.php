<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Payments\CompletePaidCartService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * KAN-10: post-payment side effects (PDF, certificate, emails)
 * run on the queue, not in the browser return.
 */
class CompletePaidCartJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 180;

    public $backoff = [15, 30, 60, 120];

    public $uniqueFor = 3600;

    /** @var int */
    public $cartId;

    /** @var int */
    public $paymentId;

    /** @var int */
    public $productId;

    public function __construct(int $cartId, int $paymentId, int $productId)
    {
        $this->cartId = $cartId;
        $this->paymentId = $paymentId;
        $this->productId = $productId;
    }

    public function uniqueId()
    {
        return 'complete-paid-cart-' . $this->cartId;
    }

    public function handle(CompletePaidCartService $completer)
    {
        $cart = Cart::find($this->cartId);
        $payment = Payment::find($this->paymentId);
        $product = Product::find($this->productId);

        if (!$cart || !$payment || !$product) {
            Log::warning('payments.complete_job.missing', [
                'cart_id' => $this->cartId,
                'payment_id' => $this->paymentId,
                'product_id' => $this->productId,
            ]);

            return;
        }

        $completer->processSideEffects($cart, $payment, $product);
    }

    public function failed(\Throwable $e)
    {
        Log::error('payments.complete_job.failed', [
            'cart_id' => $this->cartId,
            'payment_id' => $this->paymentId,
            'message' => $e->getMessage(),
        ]);
    }
}
