<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductOfferRequest;
use App\Http\Requests\UpdateProductOfferRequest;
use App\Models\ProductOffer;

class ProductOfferController extends Controller
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
     * @param  \App\Http\Requests\StoreProductOfferRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductOfferRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductOffer  $productOffer
     * @return \Illuminate\Http\Response
     */
    public function show(ProductOffer $productOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductOffer  $productOffer
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductOffer $productOffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductOfferRequest  $request
     * @param  \App\Models\ProductOffer  $productOffer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductOfferRequest $request, ProductOffer $productOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductOffer  $productOffer
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductOffer $productOffer)
    {
        //
    }
}
