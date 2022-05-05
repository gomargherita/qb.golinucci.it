<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductStepRequest;
use App\Http\Requests\UpdateProductStepRequest;
use App\Models\ProductStep;

class ProductStepController extends Controller
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
     * @param  \App\Http\Requests\StoreProductStepRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductStepRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductStep  $productStep
     * @return \Illuminate\Http\Response
     */
    public function show(ProductStep $productStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductStep  $productStep
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductStep $productStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductStepRequest  $request
     * @param  \App\Models\ProductStep  $productStep
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductStepRequest $request, ProductStep $productStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductStep  $productStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStep $productStep)
    {
        //
    }
}
