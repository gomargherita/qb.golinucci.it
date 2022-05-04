<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductDeclarationRequest;
use App\Http\Requests\UpdateProductDeclarationRequest;
use App\Models\ProductDeclaration;

class ProductDeclarationController extends Controller
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
     * @param  \App\Http\Requests\StoreProductDeclarationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductDeclarationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductDeclaration  $productDeclaration
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDeclaration $productDeclaration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductDeclaration  $productDeclaration
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDeclaration $productDeclaration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductDeclarationRequest  $request
     * @param  \App\Models\ProductDeclaration  $productDeclaration
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductDeclarationRequest $request, ProductDeclaration $productDeclaration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDeclaration  $productDeclaration
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDeclaration $productDeclaration)
    {
        //
    }
}
