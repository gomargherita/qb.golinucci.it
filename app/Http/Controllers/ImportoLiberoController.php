<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportoLiberoRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FreeAmount;



class ImportoLiberoController extends Controller
{
    public function index(){
        return Inertia::render('ImportoLibero/Index', [
            'prodottiImportoLibero' => FreeAmount::all(),
        ]);
    }

    public function create(){
        return Inertia::render('ImportoLibero/Create');
    }

    public function store(StoreImportoLiberoRequest $request){
        $validated = $request->validated();

        $freeAmount = FreeAmount::create([
            'causal' => $validated['causale'],
            'total_payable' => $validated['importo']
        ]);

        return $this->index();
    }
}