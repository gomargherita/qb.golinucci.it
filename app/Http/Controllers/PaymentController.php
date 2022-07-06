<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Notifications\ConfirmedOfflinePayment;
use App\Services\PaymentCertificateToPdfService;
use Illuminate\Support\Facades\Redirect;

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

        if ($payment->update($validated)) {
            if ($validated['paid'] && $payment->cart->product_step_id == 4) {
                // genero e salvo il pdf con proforma fattura dell'acquisto
                $payment->updatePaymentPaymentNo();
                $paymentToPdf = new PaymentCertificateToPdfService($payment->cart);
                $paymentToPdf->generateAndPersist();
                $payment->cart->user->notify(new ConfirmedOfflinePayment($payment));
                $payment->cart->updateCartStep(5);
            }
        }

        return Redirect::back()->with('success', 'Payment updated.');
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
