<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Cart;
use App\Notifications\ConfirmedOfflinePayment;
use App\Services\PaymentCertificateToPdfService;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\CartController;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $validated = $request->validated();

        $cart = Cart::find($request->cart_id);

        try{
            if ($payment->update($validated)) {

                if ($validated['paid'] && $payment->cart->product_step_id == 4) {
                    // genero e salvo il pdf con proforma fattura dell'acquisto
                    $payment->updatePaymentPaymentNo();
                    if($cart->product_id == 1){
                        $paymentToPdf = new PaymentCertificateToPdfService($payment->cart);
                        $paymentToPdf->generateAndPersist();
                        $payment->cart->user->notify(new ConfirmedOfflinePayment($payment));
                    }
                    if($cart->product_id == 2 || $cart->product_id == 3 || $cart->product_id == 4){
                        $controller = new CartController();
                        
                        $controller->step5($cart->product, $cart, $cart->user_id);
                        //CartController::step5($cart->product, $cart, $cart->user_id);
                    }
                    $payment->cart->updateCartStep(5);
                }
            }

            return Redirect::back()->with('success', 'Payment updated.');
        } catch(\Exception $e){
            return Redirect::back()->with('error', 'Errore: il numero di certificato è già usato da qualcun altro?');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return Redirect::back()->with('success', 'Payment deleted.');
    }
}
