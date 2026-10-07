<?php

namespace App\Services\Yousign;

use App\Models\Cart;
use App\Models\Signature;
use App\Notifications\FinalEmailWithAttachments;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Completes a Yousign procedure for a cart (download signed PDF, step 7, final email).
 * Used by the webhook and by the reconcile artisan command.
 */
class YousignProcedureCompletionService
{
    public const STATUS_DONE = 'procedure_finished';

    /**
     * @return array{ok: bool, reason: string, cart_ref?: string}
     */
    public function complete(Cart $cart, ?string $signatureRequestId = null): array
    {
        $cart->loadMissing(['signature', 'user', 'payment']);

        $signature = $cart->signature;
        if (!$signature) {
            return ['ok' => false, 'reason' => 'missing_signature', 'cart_ref' => $cart->cart_ref];
        }

        $procedureId = $signatureRequestId ?: $signature->procedure_id;
        if (!$procedureId) {
            return ['ok' => false, 'reason' => 'missing_procedure_id', 'cart_ref' => $cart->cart_ref];
        }

        if ($signature->procedure_id && $signature->procedure_id !== $procedureId) {
            return ['ok' => false, 'reason' => 'procedure_id_mismatch', 'cart_ref' => $cart->cart_ref];
        }

        // Idempotent: already closed
        if ($signature->status === self::STATUS_DONE && (int) $cart->product_step_id >= 7) {
            return ['ok' => true, 'reason' => 'already_completed', 'cart_ref' => $cart->cart_ref];
        }

        try {
            if ($signature->status !== self::STATUS_DONE) {
                $signature->status = self::STATUS_DONE;
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_procedure_finished')
                    ->log("Yousign procedure finished for cart {$cart->cart_ref}");
            }

            $certificateFolder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
            $fileId = $signature->file_id;
            if (!$fileId) {
                Log::error('yousign.complete.missing_file_id', ['cart_ref' => $cart->cart_ref]);
                return ['ok' => false, 'reason' => 'missing_file_id', 'cart_ref' => $cart->cart_ref];
            }

            $client = new YousignApiLaravel();
            $fileMemberSigned = $client->fileSigned($fileId, $procedureId);
            if ($fileMemberSigned === false || $fileMemberSigned === null || $fileMemberSigned === '') {
                Log::error('yousign.complete.download_failed', ['cart_ref' => $cart->cart_ref, 'procedure_id' => $procedureId]);
                return ['ok' => false, 'reason' => 'download_failed', 'cart_ref' => $cart->cart_ref];
            }

            Storage::disk('private')->put($certificateFolder . '/certificate-final.pdf', $fileMemberSigned);

            if ((int) $cart->product_step_id < 7) {
                $cart->updateCartStep(7);
            }

            if ($cart->user) {
                $cart->user->notify(new FinalEmailWithAttachments($cart));
            }

            activity()
                ->on($signature)
                ->event('signature_file_stored_finished')
                ->log("Cart {$cart->cart_ref} persisted on storage, final email sent");

            return ['ok' => true, 'reason' => 'completed', 'cart_ref' => $cart->cart_ref];
        } catch (\Throwable $e) {
            Log::error('yousign.complete.exception', [
                'cart_ref' => $cart->cart_ref,
                'message' => $e->getMessage(),
            ]);

            return ['ok' => false, 'reason' => 'exception', 'cart_ref' => $cart->cart_ref];
        }
    }

    public function findCart(?string $cartRef, ?string $signatureRequestId): ?Cart
    {
        if ($cartRef) {
            $cart = Cart::where('cart_ref', $cartRef)->first();
            if ($cart) {
                return $cart;
            }
        }

        if ($signatureRequestId) {
            $signature = Signature::with('certificate')->where('procedure_id', $signatureRequestId)->first();
            if ($signature && $signature->certificate) {
                return Cart::find($signature->certificate->cart_id);
            }
        }

        return null;
    }
}
