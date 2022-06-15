<?php

namespace App\Http\Controllers;

use App\Exports\BdxMedmalPaymentsExport;
use App\Http\Requests\StoreBordereauxRequest;
use App\Http\Requests\UpdateBordereauxRequest;
use App\Models\Bordereaux;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class BordereauxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Bordereauxs/Index', []);
    }

    public function download(Request $request)
    {
        // $requestContent = json_decode($request->getContent());
        $request_all = $request->all();
        $date = ($request_all) ? $request_all['date'] : null;

        //return Excel::download(new BdxMedmalPaymentsExport($date), 'payments.xlsx');
        return Excel::download(new BdxMedmalPaymentsExport($date), 'payments.csv');
    }
}
