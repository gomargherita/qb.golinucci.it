<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


use function PHPSTORM_META\map;

class ImportoLibero implements FromView, WithHeadingRow
{
    use Exportable;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    
    public function view(): View
    {
        $payments_data = DB::table('importo_libero_buyers')
            ->select(
                'id',
                'name',
                'surname',
                'email',
                'causale',
                'total_payable',
                'transaction_ref',
                'paid',
                'created_at',
                'updated_at',
                'profession',
                'fiscalcode',
                'dob',
                'phone',
                'via',
                'citta',
                'cap',
                'provincia',
                'declarations'
            );
            
        if (!is_null($this->date)) {
            $payments_data->whereDate('importo_libero_buyers.created_at', $this->date);
        }

        $payments = $payments_data->get();

        return view('template-bordereaux-importo-libero', [
            'payments' => $payments
        ]);
    }
}
