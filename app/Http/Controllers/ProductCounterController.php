<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCounterRequest;
use App\Http\Requests\UpdateProductCounterRequest;
use App\Models\ProductCounter;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductCounterController extends Controller
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
     * @param  \App\Http\Requests\StoreProductCounterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductCounterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCounter  $productCounter
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCounter $productCounter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCounter  $productCounter
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCounter $productCounter)
    {
        return Inertia::render('ProductCounters/Edit', [
            'product_counter' => $productCounter
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductCounterRequest  $request
     * @param  \App\Models\ProductCounter  $productCounter
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductCounterRequest $request, ProductCounter $productCounter)
    {
        $productCounter->update($request->validated());

        return Redirect::back()->with('success', 'Product Counter updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCounter  $productCounter
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCounter $productCounter)
    {
        //
    }
}
