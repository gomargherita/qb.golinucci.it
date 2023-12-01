<?php

namespace App\Http\Controllers;

use App\Exports\BdxMedmalPaymentsExport;
use App\Exports\BdxBankersErDaily;
use App\Exports\BdxBankersRI;
use App\Exports\BdxBankersSA;
use App\Exports\BdxBankersErStatic;
use App\Http\Requests\StoreBordereauxRequest;
use App\Http\Requests\UpdateBordereauxRequest;
use App\Models\Bordereaux;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Certificate;




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
        $prodotto = $request['prodotto'];
        if($prodotto == 'medici'){
            return Excel::download(new BdxMedmalPaymentsExport($date), 'payments.xlsx');
        }
        else if($prodotto == 'bancari-ER-Daily'){
            return Excel::download(new BdxBankersErDaily($date), 'payments.xlsx');
        }
        else if($prodotto == 'bancari-ER-Static'){

            return Excel::download(new BdxBankersErStatic($date), 'payments.xlsx');
        }
        else if($prodotto == 'bancari-SA'){

            return Excel::download(new BdxBankersSA($date), 'payments.xlsx');
        }
        else if($prodotto == 'bancari-RI'){

            return Excel::download(new BdxBankersRI($date), 'payments.xlsx');
        }
    }
}
