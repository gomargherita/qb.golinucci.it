<?php

namespace App\Services\Payments;

use App\Models\Cart;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Idempotent PayPal capture + cart completion.
 * Sources: browser onApprove, webhook, artisan reconcile.
 */
class PaypalCaptureService
{
    /** @var PaypalClientFactory */
    private $clients;

    /** @var CompletePaidCartService */
    private $completer;

    public function __construct(PaypalClientFactory $clients, CompletePaidCartService $completer)
    {
        $this->clients = $clients;
        $this->completer = $completer;
    }

    /**
     * Resolve payment for a cart from payment id and/or PayPal order id.
     */
    public function resolvePayment(Cart $cart, ?string $paymentId, ?string $paypalOrderId): ?Payment
    {
        if ($paypalOrderId) {
            $byOrder = Payment::where('cart_id', $cart->id)
                ->where('transaction_ref', $paypalOrderId)
                ->first();
            if ($byOrder) {
                return $byOrder;
            }
        }

        if ($paymentId) {
            return Payment::where('cart_id', $cart->id)->where('id', $paymentId)->first();
        }

        return Payment::where('cart_id', $cart->id)->latest('id')->first();
    }

    /**
     * @return array{ok: bool, info_paypal: string, reason: string}
     */
    public function captureAndComplete(Cart $cart, Payment $payment, Product $product, string $source = 'browser'): array
    {
        return DB::transaction(function () use ($cart, $payment, $product, $source) {
            /** @var Payment $locked */
            $locked = Payment::where('id', $payment->id)->lockForUpdate()->first();
            if (!$locked) {
                return $this->fail($payment, $source, 'payment_missing');
            }

            if ($locked->paid) {
                $this->event($locked, $source, 'already_paid', $locked->transaction_ref, 'COMPLETED');
                if ((int) $cart->product_step_id < 5) {
                    $this->completer->complete($cart, $locked, $product);
                }

                return ['ok' => true, 'info_paypal' => 'SUCCESS', 'reason' => 'already_paid'];
            }

            if (!$locked->transaction_ref) {
                return $this->fail($locked, $source, 'missing_transaction_ref');
            }

            $provider = $this->clients->make();

            // Prefer capture; if already captured remotely, treat as success.
            $response = $provider->capturePaymentOrder($locked->transaction_ref);
            $captureStatus = data_get($response, 'purchase_units.0.payments.captures.0.status');
            $orderStatus = data_get($response, 'status');
            $debugId = data_get($response, 'debug_id');
            $issue = data_get($response, 'details.0.issue')
                ?? data_get($response, 'name');

            Log::info('paypal.capture.result', [
                'payment_id' => $locked->id,
                'cart_ref' => $cart->cart_ref,
                'order_id' => $locked->transaction_ref,
                'capture_status' => $captureStatus,
                'order_status' => $orderStatus,
                'issue' => $issue,
                'debug_id' => $debugId,
                'source' => $source,
            ]);

            $completed = $captureStatus === 'COMPLETED' || $orderStatus === 'COMPLETED';
            $alreadyCaptured = in_array($issue, [
                'ORDER_ALREADY_CAPTURED',
                'CAPTURE_ALREADY_EXISTS',
            ], true);

            if (!$completed && $alreadyCaptured) {
                $remote = $this->safeShowOrder($provider, $locked->transaction_ref);
                $remoteStatus = data_get($remote, 'status');
                $remoteCapture = data_get($remote, 'purchase_units.0.payments.captures.0.status');
                $completed = $remoteStatus === 'COMPLETED' || $remoteCapture === 'COMPLETED';
                $captureStatus = $remoteCapture ?: $remoteStatus;
                $this->event($locked, $source, 'already_captured_remote', $locked->transaction_ref, $captureStatus, $debugId);
            }

            if (!$completed) {
                $locked->paypal_status = $captureStatus ?: ($orderStatus ?: 'FAILED');
                $locked->save();
                $this->event(
                    $locked,
                    $source,
                    'capture_failed',
                    $locked->transaction_ref,
                    $locked->paypal_status,
                    $debugId,
                    is_string($issue) ? $issue : null
                );

                return ['ok' => false, 'info_paypal' => 'FAILED', 'reason' => 'capture_not_completed'];
            }

            $locked->paypal_status = 'COMPLETED';
            $locked->last_capture_at = now();
            $locked->save();
            $this->event($locked, $source, 'capture_completed', $locked->transaction_ref, 'COMPLETED', $debugId);

            $result = $this->completer->complete($cart->fresh(), $locked->fresh(), $product);
            if (!$result['ok']) {
                return ['ok' => false, 'info_paypal' => 'FAILED', 'reason' => $result['reason']];
            }

            return ['ok' => true, 'info_paypal' => 'SUCCESS', 'reason' => 'captured'];
        });
    }

    /**
     * Reconcile without forcing capture when order is already COMPLETED remotely.
     *
     * @return array{ok: bool, info_paypal: string, reason: string}
     */
    public function reconcileFromRemote(Cart $cart, Payment $payment, Product $product, string $source = 'reconcile'): array
    {
        if ($payment->paid) {
            return ['ok' => true, 'info_paypal' => 'SUCCESS', 'reason' => 'already_paid'];
        }

        if (!$payment->transaction_ref) {
            return $this->fail($payment, $source, 'missing_transaction_ref');
        }

        $provider = $this->clients->make();
        $remote = $this->safeShowOrder($provider, $payment->transaction_ref);
        $remoteStatus = data_get($remote, 'status');
        $remoteCapture = data_get($remote, 'purchase_units.0.payments.captures.0.status');

        $this->event($payment, $source, 'remote_status', $payment->transaction_ref, $remoteCapture ?: $remoteStatus);

        if ($remoteStatus === 'COMPLETED' || $remoteCapture === 'COMPLETED') {
            return $this->captureAndComplete($cart, $payment, $product, $source);
        }

        if (in_array($remoteStatus, ['APPROVED', 'CREATED'], true)) {
            return $this->captureAndComplete($cart, $payment, $product, $source);
        }

        return ['ok' => false, 'info_paypal' => 'FAILED', 'reason' => 'remote_not_capturable:' . ($remoteStatus ?: 'unknown')];
    }

    /**
     * Create order, or reuse existing unpaid CREATED/APPROVED order for this payment.
     */
    public function createOrReuseOrder(Cart $cart, Product $product, Payment $payment): array
    {
        $provider = $this->clients->make();

        if ($payment->transaction_ref && !$payment->paid) {
            $remote = $this->safeShowOrder($provider, $payment->transaction_ref);
            $status = data_get($remote, 'status');
            if (in_array($status, ['CREATED', 'APPROVED', 'PAYER_ACTION_REQUIRED'], true)) {
                $this->event($payment, 'browser', 'order_reused', $payment->transaction_ref, $status);
                $payment->paypal_status = $status;
                $payment->save();

                return $remote;
            }
        }

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'transaction_id_' . $payment->id,
                'custom_id' => (string) $cart->cart_ref,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => (string) $cart->total_payable,
                ],
            ]],
        ]);

        if (!isset($order['id'])) {
            Log::error('paypal.create_order.failed', [
                'payment_id' => $payment->id,
                'debug_id' => data_get($order, 'debug_id'),
                'name' => data_get($order, 'name'),
            ]);
            $this->event($payment, 'browser', 'order_create_failed', null, data_get($order, 'name'), data_get($order, 'debug_id'));

            return $order;
        }

        $payment->transaction_ref = $order['id'];
        $payment->paypal_status = data_get($order, 'status', 'CREATED');
        $payment->save();
        $this->event($payment, 'browser', 'order_created', $order['id'], $payment->paypal_status);

        return $order;
    }

    private function safeShowOrder($provider, string $orderId): ?array
    {
        try {
            if (method_exists($provider, 'show')) {
                $remote = $provider->show($orderId);
            } elseif (method_exists($provider, 'showOrderDetails')) {
                $remote = $provider->showOrderDetails($orderId);
            } else {
                return null;
            }

            return is_array($remote) ? $remote : null;
        } catch (\Throwable $e) {
            Log::warning('paypal.show_order.failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function fail(Payment $payment, string $source, string $reason): array
    {
        $this->event($payment, $source, $reason, $payment->transaction_ref, $payment->paypal_status);

        return ['ok' => false, 'info_paypal' => 'FAILED', 'reason' => $reason];
    }

    private function event(
        Payment $payment,
        string $source,
        string $event,
        ?string $orderId = null,
        ?string $status = null,
        ?string $debugId = null,
        ?string $message = null
    ): void {
        PaymentEvent::create([
            'payment_id' => $payment->id,
            'source' => $source,
            'event' => $event,
            'paypal_order_id' => $orderId,
            'paypal_status' => $status,
            'debug_id' => $debugId,
            'message' => $message,
        ]);
    }
}
