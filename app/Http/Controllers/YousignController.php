<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Notifications\FinalEmailWithAttachments;
use App\Services\Yousign\YousignApiLaravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class YousignController extends Controller
{

    // member.finished
    /** 
     * problema notifica contemporanea a procedure.finished
     * da yousign Stefano Negrini suggerisce:
     * - quando avete un solo firmatario, settare il webhook a livello di member e procedura è ridondante, non serve (a voi di scegliere se utilizzare solo uno o l'altro)
     * - quando avete più firmatari potete ma non siete obbligati ad utilizzarli entrambi (e.g. con procedura ne riceverete solo uno al completamento del tutto - con firmatari paralleli, o potete considerare la procedura completa quando tutti i webhook member sono stati effettuatidi usare direttamente 
     */
    // public function yousign_member_finished(Request $request)
    // {
    //     if (!empty($request->header('x-cart-header'))) {
    //         $cart_header = $request->header('x-cart-header');
    //         $cart = Cart::where('cart_ref', $cart_header)->first();
    //         if ($cart && $cart->signature->status == 'server_stamp_ok') {
    //             $signature = $cart->signature;
    //             $signature->status = 'member_ok';
    //             $signature->save();
    //             activity()
    //                 ->on($signature)
    //                 ->event('signature_member_finished')
    //                 ->log("Yousign member sign finished for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
    //         }
    //     }
    // }

    // procedure.finished
    public function yousign_procedure_finished(Request $request)
    {
        
        $signatureRequestId = $request->input('signature_request_id');
        $cart_header = $request->input('cart_ref');

            $cart = Cart::where('cart_ref', $cart_header)->first();
            
            if ($cart->signature->procedure_id == $signatureRequestId) {
                $signature = $cart->signature;
                $signature->status = 'procedure_finished';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_procedure_finished')
                    ->log("Yousign procedure finished for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
                
                $certificate_folder = 'certificates/user_' . $cart->user->id . '/cart_' . $cart->id;
                $client = new YousignApiLaravel();
                $file_id = $cart->signature->file_id;
                $fileMemberSigned = $client->fileSigned($file_id, $signatureRequestId);
                Storage::disk('private')->put($certificate_folder . '/certificate-final.pdf', $fileMemberSigned);
                $cart->updateCartStep(7);
                $cart->user->notify(new FinalEmailWithAttachments($cart));
                activity()
                    ->on($signature)
                    ->event('signature_file_stored_finished')
                    ->log("Cart $cart->cart_ref - certificate {$cart->payment->payment_no} persisted on storage, final email sent;");
            
        }
    }

    // procedure.error
    public function yousign_procedure_error(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            $cart = Cart::where('cart_ref', $cart_header)->first();
            $signature = $cart->signature;
            $signature->status = 'procedure_error';
            $signature->save();
            activity()
                ->on($signature)
                ->event('signature_procedure_error')
                ->log("Yousign procedure error for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
        }
    }

    // procedure.expired
    public function yousign_procedure_expired(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            $cart = Cart::where('cart_ref', $cart_header)->first();
            if ($cart && $cart->signature->status == 'server_stamp_ok') {
                $signature = $cart->signature;
                $signature->status = 'procedure_expired';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_procedure_expired')
                    ->log("Yousign procedure expired for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
            }
        }
    }

    // procedure.refused
    public function yousign_procedure_refused(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            $cart = Cart::where('cart_ref', $cart_header)->first();
            if ($cart && $cart->signature->status == 'server_stamp_ok') {
                $signature = $cart->signature;
                $signature->status = 'procedure_refused';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_member_refused')
                    ->log("Yousign procedure refused for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
            }
        }
    }

    // server_stamp.finished
    public function yousign_server_stamps_finished(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            $cart = Cart::where('cart_ref', $cart_header)->first();
            if ($cart && $cart->signature->status == 'init') {
                $signature = $cart->signature;
                $signature->status = 'server_stamp_ok';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_stamp_ok')
                    ->log("Yousign server stamp generation ok for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
            }
        }
    }

    // server_stamp.error
    public function yousign_server_stamps_error(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            $cart = Cart::where('cart_ref', $cart_header)->first();
            if ($cart && $cart->signature->status == 'init') {
                $signature = $cart->signature;
                $signature->status = 'server_stamp_error';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_stamp_error')
                    ->log("Yousign server stamp generation error for cart $cart->cart_ref - certificate {$cart->payment->payment_no};");
            }
        }
    }
}
