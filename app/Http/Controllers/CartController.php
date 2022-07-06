<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\ProductStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Carts/Index', [
            'filters' => Request::all('search', 'steps', 'trashed'),
            'all_product_steps' => ProductStep::all(), // TODO questa cosa va rivalutata quando verranno creati nuovi prodotti
            'carts' => Auth::user()->account->carts()
                ->orderByDate()
                ->filter(Request::only('search', 'steps', 'trashed'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($cart) => [
                    'id' => $cart->id,
                    'user' => $cart->user->only('id', 'email'),
                    'product_name' => $cart->product->name,
                    'step' => $cart->productStep ? $cart->productStep->step_number . '. ' . $cart->productStep->step_name : null,
                    'product_offer' => $cart->productOffer ? $cart->productOffer->id . '. ' . $cart->productOffer->name : null,
                    'cart_ref' => $cart->cart_ref,
                    'created_at' => $cart->created_at->format('d/m/Y'),
                    'deleted_at' => $cart->deleted_at,
                ]),
        ]);
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
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        $payment_gateways = PaymentGateway::all();
        $certificate_folder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
        $payment = Payment::where('cart_id', $cart->id)->first();

        return Inertia::render('Carts/Edit', [
            // 'can' => [
            //     'delete_cart' => $currentUser->can('carts.delete')
            // ],
            'cart' => [
                'id' => $cart->id,
                'product' => $cart->product,
                'product_offer' => $cart->productOffer ? $cart->productOffer : null,
                'product_step_id' => $cart->product_step_id,
                'product_steps' => $cart->product->productSteps,
                'payment' => ($payment) ?
                    [
                        'id' => $payment->id,
                        'paid' => $payment->paid,
                        'paid_at' => $payment->paid_at?->format('Y-m-d'),
                        'payment_gateway_id' => $payment->payment_gateway_id,
                        'payment_no' => $payment->payment_no,
                        'total_payable' => $payment->total_payable,
                        'transaction_ref' => $payment->transaction_ref
                    ]
                    : null,
                'certificate' => $cart->certificate,
                'cart_ref' => $cart->cart_ref,
                'deleted_at' => $cart->deleted_at,
                'documents' => [
                    'certificate_final' => Storage::disk('private')->exists($certificate_folder . '/' . 'certificate-final.pdf') ? route('privateFiles', $certificate_folder . '/' . 'certificate-final.pdf') : null,
                    'declarations' => Storage::disk('private')->exists($certificate_folder . '/' . 'declarations.pdf') ? route('privateFiles', $certificate_folder . '/' . 'declarations.pdf') : null,
                    'payment_certificate' => Storage::disk('private')->exists($certificate_folder . '/' . 'payment-certificate.pdf') ? route('privateFiles', $certificate_folder . '/' . 'payment-certificate.pdf') : null,
                ]
            ],
            'payment_gateways' => $payment_gateways,
            'product_counters' => $cart->product->productCounters,
            'user' => $cart->user ? $cart->user : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $cart->update($request->validated());

        return Redirect::back()->with('success', 'Cart updated.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return Redirect::back()->with('success', 'Cart deleted.');
    }

    public function restore(Cart $cart)
    {
        $cart->restore();

        return Redirect::back()->with('success', 'Cart restored.');
    }
}
