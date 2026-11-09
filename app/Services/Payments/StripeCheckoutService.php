<?php

namespace App\Services\Payments;

use App\Models\Cart;
use App\Models\ImportoLiberoBuyer;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\PaymentGateway;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Stripe\Webhook;
use Throwable;

class StripeCheckoutService
{
    public function enabled(): bool
    {
        return (bool) config('stripe.enabled') && (string) config('stripe.secret') !== '';
    }

    public function gatewayId(): int
    {
        $gw = PaymentGateway::query()->firstOrCreate(
            ['name' => 'Stripe'],
            ['public' => 1]
        );

        return (int) $gw->id;
    }

    public function createCartSession(Cart $cart, Payment $payment, Product $product): Session
    {
        $session = $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'locale' => 'it',
            'payment_method_types' => ['card', 'paypal', 'klarna'],
            'success_url' => route('front.product.buy', [
                'product' => $product->id,
                'cart' => $cart->cart_ref,
            ]) . '?stripe=success',
            'cancel_url' => route('front.product.buy', [
                'product' => $product->id,
                'cart' => $cart->cart_ref,
            ]) . '?stripe=cancel',
            'client_reference_id' => (string) $cart->cart_ref,
            'metadata' => [
                'qb_kind' => 'cart',
                'cart_ref' => (string) $cart->cart_ref,
                'payment_id' => (string) $payment->id,
                'product_id' => (string) $product->id,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => config('stripe.currency', 'eur'),
                    'unit_amount' => $this->cents($cart->total_payable),
                    'product_data' => [
                        'name' => $product->name ?: ('QB ' . $cart->cart_ref),
                    ],
                ],
            ]],
        ]);

        $payment->transaction_ref = $session->id;
        $payment->paypal_status = $session->status ?: 'open';
        $payment->save();

        PaymentEvent::create([
            'payment_id' => $payment->id,
            'source' => 'browser',
            'event' => 'stripe_session_created',
            'paypal_order_id' => $session->id,
            'paypal_status' => $session->status,
        ]);

        return $session;
    }

    public function createImportoLiberoSession(ImportoLiberoBuyer $buyer): Session
    {
        $session = $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'locale' => 'it',
            'payment_method_types' => ['card', 'paypal', 'klarna'],
            'success_url' => url('/pagaonline/' . $buyer->id) . '?stripe=success',
            'cancel_url' => url('/pagaonline/' . $buyer->id) . '?stripe=cancel',
            'client_reference_id' => 'il_' . $buyer->id,
            'metadata' => [
                'qb_kind' => 'importo_libero',
                'buyer_id' => (string) $buyer->id,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => config('stripe.currency', 'eur'),
                    'unit_amount' => $this->cents($buyer->total_payable),
                    'product_data' => [
                        'name' => $buyer->causale ?: ('Pagamento QB #' . $buyer->id),
                    ],
                ],
            ]],
        ]);

        $buyer->transaction_ref = $session->id;
        $buyer->save();

        return $session;
    }

    /**
     * @return array{ok: bool, reason: string}
     */
    public function completeFromWebhookPayload(string $payload, string $signature): array
    {
        $secret = (string) config('stripe.webhook_secret');
        if ($secret === '') {
            Log::warning('stripe.webhook.missing_secret');

            return ['ok' => false, 'reason' => 'missing_webhook_secret'];
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (Throwable $e) {
            Log::warning('stripe.webhook.invalid', ['message' => $e->getMessage()]);

            return ['ok' => false, 'reason' => 'invalid_signature'];
        }

        if (!in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            return ['ok' => true, 'reason' => 'ignored'];
        }

        /** @var Session $session */
        $session = $event->data->object;

        return $this->completeSession($session);
    }

    /**
     * @return array{ok: bool, reason: string}
     */
    public function completeSession(Session $session): array
    {
        if (($session->payment_status ?? '') !== 'paid' && ($session->status ?? '') !== 'complete') {
            return ['ok' => false, 'reason' => 'not_paid'];
        }

        $kind = (string) ($session->metadata->qb_kind ?? '');

        if ($kind === 'importo_libero') {
            $buyer = ImportoLiberoBuyer::find((int) ($session->metadata->buyer_id ?? 0));
            if (!$buyer) {
                return ['ok' => false, 'reason' => 'buyer_missing'];
            }
            $buyer->paid = true;
            $buyer->transaction_ref = $session->id;
            $buyer->save();

            return ['ok' => true, 'reason' => 'importo_libero_paid'];
        }

        $payment = Payment::where('id', (int) ($session->metadata->payment_id ?? 0))->first()
            ?: Payment::where('transaction_ref', $session->id)->first();

        if (!$payment) {
            return ['ok' => false, 'reason' => 'payment_missing'];
        }

        $cart = Cart::find($payment->cart_id);
        $product = $cart ? Product::find($cart->product_id) : null;
        if (!$cart || !$product) {
            return ['ok' => false, 'reason' => 'cart_missing'];
        }

        if ($payment->paid) {
            return ['ok' => true, 'reason' => 'already_paid'];
        }

        $payment->transaction_ref = $session->id;
        $payment->paypal_status = 'COMPLETED';
        $payment->save();
        $payment->updatePaymentStatus();

        PaymentEvent::create([
            'payment_id' => $payment->id,
            'source' => 'webhook',
            'event' => 'stripe_paid',
            'paypal_order_id' => $session->id,
            'paypal_status' => 'paid',
        ]);

        app(CompletePaidCartService::class)->dispatchAfterPaid($cart->fresh(), $payment->fresh(), $product);

        return ['ok' => true, 'reason' => 'captured'];
    }

    private function client(): StripeClient
    {
        return new StripeClient((string) config('stripe.secret'));
    }

    private function cents($amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
