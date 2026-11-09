<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Services\Yousign\YousignProcedureCompletionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * KAN-5 / KAN-10: YouSign procedure.finished work on Redis (PDF, step 7, email).
 */
class ProcessYousignProcedureFinishedJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 120;

    public $backoff = [15, 30, 60, 120];

    public $uniqueFor = 600;

    /** @var int */
    public $cartId;

    /** @var string|null */
    public $signatureRequestId;

    public function __construct(int $cartId, ?string $signatureRequestId = null)
    {
        $this->cartId = $cartId;
        $this->signatureRequestId = $signatureRequestId;
    }

    public function uniqueId()
    {
        return 'yousign-procedure-finished-' . $this->cartId;
    }

    public function handle(YousignProcedureCompletionService $completion): void
    {
        $cart = Cart::find($this->cartId);
        if (!$cart) {
            Log::warning('yousign.complete_job.missing', ['cart_id' => $this->cartId]);
            return;
        }

        $result = $completion->complete($cart, $this->signatureRequestId);
        Log::info('yousign.complete_job', $result);

        if (empty($result['ok'])) {
            throw new \RuntimeException('yousign.complete_job.' . ($result['reason'] ?? 'failed'));
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('yousign.complete_job.failed', [
            'cart_id' => $this->cartId,
            'message' => $e->getMessage(),
        ]);
    }
}
