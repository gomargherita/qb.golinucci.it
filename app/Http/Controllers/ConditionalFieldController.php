<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConditionalFieldRequest;
use App\Http\Requests\UpdateConditionalFieldRequest;
use App\Models\ConditionalField;

class ConditionalFieldController extends Controller
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
     * @param  \App\Http\Requests\StoreConditionalFieldRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConditionalFieldRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConditionalField  $conditionalField
     * @return \Illuminate\Http\Response
     */
    public function show(ConditionalField $conditionalField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConditionalField  $conditionalField
     * @return \Illuminate\Http\Response
     */
    public function edit(ConditionalField $conditionalField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConditionalFieldRequest  $request
     * @param  \App\Models\ConditionalField  $conditionalField
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConditionalFieldRequest $request, ConditionalField $conditionalField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConditionalField  $conditionalField
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConditionalField $conditionalField)
    {
        //
    }
}
