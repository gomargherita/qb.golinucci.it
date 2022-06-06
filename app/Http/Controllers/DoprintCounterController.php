<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoprintCounterRequest;
use App\Http\Requests\UpdateDoprintCounterRequest;
use App\Models\DoprintCounter;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DoprintCounterController extends Controller
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
     * @param  \App\Http\Requests\StoreDoprintCounterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDoprintCounterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DoprintCounter  $doprintCounter
     * @return \Illuminate\Http\Response
     */
    public function show(DoprintCounter $doprintCounter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoprintCounter  $doprintCounter
     * @return \Illuminate\Http\Response
     */
    public function edit(DoprintCounter $doprintCounter)
    {
        return Inertia::render('DoprintCounters/Edit', [
            'doprint_counter' => $doprintCounter
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDoprintCounterRequest  $request
     * @param  \App\Models\DoprintCounter  $doprintCounter
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDoprintCounterRequest $request, DoprintCounter $doprintCounter)
    {
        $doprintCounter->update($request->validated());

        return Redirect::back()->with('success', 'Doprint Counter updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DoprintCounter  $doprintCounter
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoprintCounter $doprintCounter)
    {
        //
    }
}
