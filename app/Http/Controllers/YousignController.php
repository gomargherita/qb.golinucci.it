<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class YousignController extends Controller
{

    public function yousign_member_finished(Request $request)
    {
    }

    public function yousign_procedure_finished(Request $request)
    {
    }

    public function yousign_procedure_refused(Request $request)
    {
        
    }

    public function yousign_server_stamps_finished(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            // if($request->eventName ==  "server_stamp.finished" && $request->serverStamp->status == 'done'){
            $cart = Cart::where('cart_ref', $cart_header)->first();
            // porto lo status della firma a 'server_stamp'
            if ($cart && $cart->signature->status == 'init') {
                $signature = $cart->signature;
                $signature->status = 'server_stamp_ok';
                $signature->save();
            }
            // }
        }
    }

    public function yousign_server_stamps_error(Request $request)
    {
        if (!empty($request->header('x-cart-header'))) {
            $cart_header = $request->header('x-cart-header');
            // if($request->eventName ==  "server_stamp.finished" && $request->serverStamp->status == 'done'){
            $cart = Cart::where('cart_ref', $cart_header)->first();
            // porto lo status della firma a 'server_stamp'
            // if ($cart && $cart->signature->status == 'init') {
            //     $signature = $cart->signature;
            //     $signature->status = 'server_stamp_ok';
            //     $signature->save();
            // }
            // }
        }
    }
}
