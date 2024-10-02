<?php

namespace App\Http\Controllers;

use App\Exports\BdxMedmalPaymentsExport;
use App\Exports\BdxMedmalPaymentsExportMensile;
use App\Exports\BdxBankersErDaily;
use App\Exports\BdxBankersRI;
use App\Exports\ImportoLibero;
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
use Illuminate\Support\Facades\Log;
use App\Models\ImportoLiberoBuyer;




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
        //cancella doppioni
        DB::statement("DELETE FROM product_client_details as pcd WHERE pcd.client_detail_id IN (
            SELECT id
            FROM client_details
            WHERE dob IS NULL
              AND EXISTS (
                SELECT 1
                FROM client_details cd2
                WHERE cd2.user_id = client_details.user_id
                  AND cd2.gender IS NOT NULL
              ));");
            
            DB::statement("DELETE FROM client_details
            WHERE dob IS NULL
              AND EXISTS (
                SELECT *
                FROM (SELECT * FROM client_details) AS cd2
                WHERE cd2.user_id = client_details.user_id
                  AND cd2.gender IS NOT NULL
              );");

        // $requestContent = json_decode($request->getContent());
        $request_all = $request->all();
        $date = ($request_all) ? $request_all['date'] : null;
        $prodotto = $request['prodotto'];
        if($prodotto == 'medici'){
            return Excel::download(new BdxMedmalPaymentsExport($date), 'payments.xlsx');
        }
        else if($prodotto == 'medici-mensile'){
            return Excel::download(new BdxMedmalPaymentsExportMensile($date), 'payments.xlsx');
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
        else if($prodotto == 'importo_libero'){
            return Excel::download(new ImportoLibero($date), 'importo_libero.xlsx');
        }
    }

    public function importo_liberoadmin(){
        $importi = ImportoLiberoBuyer::all();

        $table = "<table style='border: 1px solid black;'><tr><th>id</th><th>name</th><th>surname</th><th>email</th><th>causale</th><th>total_payable</th><th>transaction_ref</th><th>paid</th><th>created_at</th><th>updated_at</th><th>deleted_at</th><th>profession</th><th>fiscalcode</th><th>dob</th><th>phone</th><th>via</th><th>citta</th><th>cap</th><th>provincia</th><th>declarations</th></tr>";
        
        foreach($importi as $importo){
            $table = $table . '<tr>';
            $table = $table . "<td>" . $importo->id . "</td>";
            $table = $table . "<td>" . $importo->name . "</td>";
            $table = $table . "<td>" . $importo->surname . "</td>";
            $table = $table . "<td>" . $importo->email . "</td>";
            $table = $table . "<td>" . $importo->causale . "</td>";
            $table = $table . "<td>" . $importo->total_payable . "</td>";
            $table = $table . "<td>" . $importo->transaction_ref . "</td>";
            $table = $table . "<td>" . $importo->paid . "</td>";
            $table = $table . "<td>" . $importo->created_at . "</td>";
            $table = $table . "<td>" . $importo->updated_at . "</td>";
            $table = $table . "<td>" . $importo->deleted_at . "</td>";
            $table = $table . "<td>" . $importo->profession . "</td>";
            $table = $table . "<td>" . $importo->fiscalcode . "</td>";
            $table = $table . "<td>" . $importo->dob . "</td>";
            $table = $table . "<td>" . $importo->phone . "</td>";
            $table = $table . "<td>" . $importo->via . "</td>";
            $table = $table . "<td>" . $importo->citta . "</td>";
            $table = $table . "<td>" . $importo->cap . "</td>";
            $table = $table . "<td>" . $importo->provincia . "</td>";
            $table = $table . "<td>" . $importo->declarations . "</td>";
            $table = $table . '</tr>';
        }

        $table = $table . "</table>";

        echo $table;

    }
}
