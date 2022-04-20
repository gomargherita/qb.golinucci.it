<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductConditionRequest;
use App\Http\Requests\UpdateProductConditionRequest;
use App\Models\ProductCondition;

class ProductConditionController extends Controller
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
     * @param  \App\Http\Requests\StoreProductConditionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductConditionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCondition  $productCondition
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCondition $productCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCondition  $productCondition
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCondition $productCondition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductConditionRequest  $request
     * @param  \App\Models\ProductCondition  $productCondition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductConditionRequest $request, ProductCondition $productCondition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCondition  $productCondition
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCondition $productCondition)
    {
        //
    }
}
