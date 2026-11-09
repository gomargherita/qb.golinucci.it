<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Services\Yousign\YousignApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class StartYousignCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 120;

    /** @var int */
    public $cartId;

    public function __construct(int $cartId)
    {
        $this->cartId = $cartId;
    }

    public function handle(): void
    {
        $cart = Cart::with([
            'user.clientDetails',
            'certificate.signature',
            'productOffer.contract',
            'payment',
        ])->find($this->cartId);

        if (!$cart || !$cart->user || !$cart->certificate) {
            Log::warning('yousign.start_job.missing', ['cart_id' => $this->cartId]);
            return;
        }

        $link = (new YousignApiService($cart, $cart->user))->startYousignClientAndLaunchProcedure();
        Log::info('yousign.start_job.ok', [
            'cart_ref' => $cart->cart_ref,
            'link_set' => is_string($link) && $link !== '',
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('yousign.start_job.failed', [
            'cart_id' => $this->cartId,
            'message' => $e->getMessage(),
        ]);
    }
}
