<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Payment;
use App\Services\Payments\PaypalCaptureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * PayPal server-to-server notifications (KAN-6).
 * Completes carts when the browser never calls the return URL.
 */
class PaypalWebhookController extends Controller
{
    public function __invoke(Request $request, PaypalCaptureService $capture)
    {
        $eventType = (string) $request->input('event_type', '');
        $resource = $request->input('resource', []);

        Log::info('paypal.webhook.received', [
            'event_type' => $eventType,
            'event_id' => $request->input('id'),
            'resource_id' => data_get($resource, 'id'),
        ]);

        $interesting = [
            'CHECKOUT.ORDER.APPROVED',
            'CHECKOUT.ORDER.COMPLETED',
            'PAYMENT.CAPTURE.COMPLETED',
        ];

        if (!in_array($eventType, $interesting, true)) {
            return response()->json(['ok' => true, 'reason' => 'ignored']);
        }

        $orderId = $this->extractOrderId($eventType, $resource);
        if (!$orderId) {
            return response()->json(['ok' => false, 'reason' => 'missing_order_id'], 422);
        }

        $payment = Payment::where('transaction_ref', $orderId)->first();
        if (!$payment) {
            // Capture webhooks may carry supplementary data with order id
            $custom = data_get($resource, 'custom_id') ?: data_get($resource, 'supplementary_data.related_ids.order_id');
            if ($custom) {
                $payment = Payment::where('transaction_ref', $custom)->first()
                    ?: Payment::whereHas('cart', function ($q) use ($custom) {
                        $q->where('cart_ref', $custom);
                    })->latest('id')->first();
            }
        }

        if (!$payment) {
            Log::warning('paypal.webhook.payment_not_found', ['order_id' => $orderId, 'event_type' => $eventType]);

            return response()->json(['ok' => true, 'reason' => 'payment_not_found']);
        }

        $cart = Cart::with('product')->find($payment->cart_id);
        if (!$cart || !$cart->product) {
            return response()->json(['ok' => false, 'reason' => 'cart_missing'], 422);
        }

        $result = $capture->captureAndComplete($cart, $payment, $cart->product, 'webhook');

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    private function extractOrderId(string $eventType, $resource): ?string
    {
        if (!is_array($resource)) {
            return null;
        }

        if (str_starts_with($eventType, 'CHECKOUT.ORDER.')) {
            return $resource['id'] ?? null;
        }

        return data_get($resource, 'supplementary_data.related_ids.order_id')
            ?: data_get($resource, 'custom_id')
            ?: null;
    }
}
