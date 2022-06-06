<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
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
            'filters' => Request::all('search', 'trashed'),
            'carts' => Auth::user()->account->carts()
                ->orderByDate()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
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
                'payment' => $cart->payment,
                'cart_ref' => $cart->cart_ref,
                'deleted_at' => $cart->deleted_at,
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
