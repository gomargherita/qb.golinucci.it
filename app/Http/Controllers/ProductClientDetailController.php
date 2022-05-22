<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductClientDetailRequest;
use App\Http\Requests\UpdateProductClientDetailRequest;
use App\Models\ProductClientDetail;

class ProductClientDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreProductClientDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductClientDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductClientDetail  $productClientDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ProductClientDetail $productClientDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductClientDetail  $productClientDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductClientDetail $productClientDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductClientDetailRequest  $request
     * @param  \App\Models\ProductClientDetail  $productClientDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductClientDetailRequest $request, ProductClientDetail $productClientDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductClientDetail  $productClientDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductClientDetail $productClientDetail)
    {
        //
    }
}
