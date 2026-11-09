<?php

namespace App\Http\Controllers;

use App\Services\Payments\StripeCheckoutService;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, StripeCheckoutService $stripe)
    {
        $result = $stripe->completeFromWebhookPayload(
            $request->getContent(),
            (string) $request->header('Stripe-Signature', '')
        );

        $ok = !empty($result['ok']);
        $reason = (string) ($result['reason'] ?? '');

        if (!$ok && in_array($reason, ['invalid_signature', 'missing_webhook_secret'], true)) {
            return response()->json($result, 400);
        }

        return response()->json($result, $ok ? 200 : 422);
    }
}
