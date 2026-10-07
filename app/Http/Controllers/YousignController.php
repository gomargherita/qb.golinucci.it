<?php

namespace App\Http\Controllers;

use App\Services\Yousign\YousignProcedureCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YousignController extends Controller
{
    // procedure.finished / signature_request.done
    public function yousign_procedure_finished(Request $request, YousignProcedureCompletionService $completion)
    {
        $signatureRequestId = $this->extractSignatureRequestId($request);
        $cartRef = $this->extractCartRef($request);

        $cart = $completion->findCart($cartRef, $signatureRequestId);
        if (!$cart) {
            Log::warning('yousign.webhook.cart_not_found', [
                'cart_ref' => $cartRef,
                'signature_request_id' => $signatureRequestId,
                'payload_keys' => array_keys($request->all()),
            ]);

            return response()->json(['ok' => false, 'reason' => 'cart_not_found'], 404);
        }

        $result = $completion->complete($cart, $signatureRequestId);

        Log::info('yousign.webhook.procedure_finished', $result);

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    // procedure.error
    public function yousign_procedure_error(Request $request)
    {
        return $this->markSignatureFromCartHeader($request, 'procedure_error', 'signature_procedure_error', null);
    }

    // procedure.expired
    public function yousign_procedure_expired(Request $request)
    {
        return $this->markSignatureFromCartHeader($request, 'procedure_expired', 'signature_procedure_expired', 'server_stamp_ok');
    }

    // procedure.refused
    public function yousign_procedure_refused(Request $request)
    {
        return $this->markSignatureFromCartHeader($request, 'procedure_refused', 'signature_member_refused', 'server_stamp_ok');
    }

    // server_stamp.finished
    public function yousign_server_stamps_finished(Request $request)
    {
        return $this->markSignatureFromCartHeader($request, 'server_stamp_ok', 'signature_stamp_ok', 'init');
    }

    // server_stamp.error
    public function yousign_server_stamps_error(Request $request)
    {
        return $this->markSignatureFromCartHeader($request, 'server_stamp_error', 'signature_stamp_error', 'init');
    }

    // member.finished — kept as no-op endpoint for dashboard compatibility
    public function yousign_member_finished(Request $request)
    {
        Log::info('yousign.webhook.member_finished_ignored', [
            'cart_ref' => $this->extractCartRef($request),
        ]);

        return response()->json(['ok' => true, 'reason' => 'ignored_use_procedure_finished']);
    }

    private function markSignatureFromCartHeader(Request $request, string $newStatus, string $event, ?string $requiredStatus)
    {
        $cartRef = $this->extractCartRef($request);
        if (!$cartRef) {
            return response()->json(['ok' => false, 'reason' => 'missing_cart_ref'], 422);
        }

        $cart = \App\Models\Cart::where('cart_ref', $cartRef)->first();
        if (!$cart || !$cart->signature) {
            return response()->json(['ok' => false, 'reason' => 'cart_or_signature_missing'], 404);
        }

        $signature = $cart->signature;
        if ($requiredStatus !== null && $signature->status !== $requiredStatus) {
            return response()->json([
                'ok' => true,
                'reason' => 'status_not_applicable',
                'current' => $signature->status,
            ]);
        }

        $signature->status = $newStatus;
        $signature->save();
        activity()
            ->on($signature)
            ->event($event)
            ->log("Yousign {$newStatus} for cart {$cart->cart_ref}");

        return response()->json(['ok' => true, 'reason' => $newStatus, 'cart_ref' => $cart->cart_ref]);
    }

    private function extractSignatureRequestId(Request $request): ?string
    {
        $candidates = [
            $request->input('signature_request_id'),
            $request->input('procedure_id'),
            data_get($request->all(), 'data.signature_request.id'),
            data_get($request->all(), 'signature_request.id'),
            data_get($request->all(), 'data.id'),
        ];

        foreach ($candidates as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function extractCartRef(Request $request): ?string
    {
        $candidates = [
            $request->input('cart_ref'),
            $request->header('x-cart-header'),
            $request->header('X-Custom-Header'),
            $request->header('X-Cart-Ref'),
            data_get($request->all(), 'data.signature_request.external_id'),
        ];

        foreach ($candidates as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
