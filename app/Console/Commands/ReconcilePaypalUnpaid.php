<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Payments\PaypalCaptureService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Recovery for PayPal payments left unpaid while the order may be COMPLETED remotely (KAN-6).
 */
class ReconcilePaypalUnpaid extends Command
{
    protected $signature = 'paypal:reconcile-unpaid
                            {--apply : Complete payments that PayPal reports as capturable/completed}
                            {--limit=50 : Max payments to inspect}';

    protected $description = 'Reconcile unpaid PayPal payments against remote order status';

    public function handle(PaypalCaptureService $capture): int
    {
        $apply = (bool) $this->option('apply');
        $limit = max(1, (int) $this->option('limit'));

        $payments = Payment::query()
            ->with(['cart.product'])
            ->where('paid', 0)
            ->where('payment_gateway_id', 1)
            ->whereNotNull('transaction_ref')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $this->info(($apply ? 'APPLY' : 'DRY-RUN') . " — inspecting {$payments->count()} payment(s)");

        $ok = 0;
        $skip = 0;
        $fail = 0;

        foreach ($payments as $payment) {
            $cart = $payment->cart;
            if (!$cart || !$cart->product) {
                $this->warn("payment={$payment->id} — cart/product missing");
                $fail++;
                continue;
            }

            if (!$apply) {
                $this->line("CANDIDATE payment={$payment->id} cart={$cart->cart_ref} order={$payment->transaction_ref} paypal_status={$payment->paypal_status}");
                $ok++;
                continue;
            }

            $result = $capture->reconcileFromRemote($cart, $payment, $cart->product, 'reconcile');
            Log::info('paypal.reconcile', array_merge($result, [
                'payment_id' => $payment->id,
                'cart_ref' => $cart->cart_ref,
            ]));

            if ($result['ok']) {
                $this->info("OK payment={$payment->id} reason={$result['reason']}");
                $ok++;
            } elseif (str_starts_with($result['reason'], 'remote_not_capturable')) {
                $this->line("SKIP payment={$payment->id} {$result['reason']}");
                $skip++;
            } else {
                $this->error("FAIL payment={$payment->id} {$result['reason']}");
                $fail++;
            }
        }

        $this->info("Summary: ok={$ok} skipped={$skip} failed={$fail}");

        return $fail > 0 ? 1 : 0;
    }
}
