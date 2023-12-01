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

class BdxBankersErDaily implements FromView, WithHeadingRow
{
    use Exportable;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    
    public function view(): View
    {
        $payments_data = DB::table('payments')
            ->join('carts', 'payments.cart_id', '=', 'carts.id')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('certificates', 'carts.id', '=', 'certificates.cart_id')
            ->join('users', 'carts.user_id', '=', 'users.id')
            ->join('client_details', 'users.id', '=', 'client_details.user_id')
            ->leftJoin('bordereaux_er_info', 'bordereaux_er_info.product_offer_id', "=", "carts.product_offer_id")
            
            ->leftJoin('product_client_details as pcd5', function($join) {
                $join->on('client_details.id', '=', 'pcd5.client_detail_id');
                $join->where('pcd5.conditional_field_id', 5);
            })
            ->leftJoin('product_client_details as pcd6', function($join) {
                $join->on('client_details.id', '=', 'pcd6.client_detail_id');
                $join->where('pcd6.conditional_field_id', 6);
            })
            ->leftJoin('product_client_details as pcd7', function($join) {
                $join->on('client_details.id', '=', 'pcd7.client_detail_id');
                $join->where('pcd7.conditional_field_id', 7);
            })
            ->leftJoin('product_client_details as pcd8', function($join) {
                $join->on('client_details.id', '=', 'pcd8.client_detail_id');
                $join->where('pcd8.conditional_field_id', 8);
            })
            ->select(
                'carts.product_id as pid',

                'client_details.first_name',
                'client_details.last_name',
                'client_details.fiscal_code',
                'client_details.address',
                'client_details.postal_code',
                'client_details.city',
                'client_details.province',
                'users.email',
                'pcd5.selected_field_value as office_phone',
                'client_details.phone as personal_phone',
                'pcd6.selected_field_value as first_appartenenza',
                'pcd7.selected_field_value as bank',
                'pcd8.selected_field_value as abi',
                'payments.payment_no',
                'payments.paid_at',
                'carts.renewal_date', // if renewal_date null uso payment_at // 'certificates.start_validity_date',
                // 'certificates.end_validity_date',
                //END DATE
                'certificates.end_validity_date',
                'bordereaux_er_info.Q',
                'bordereaux_er_info.R',
                'bordereaux_er_info.S',
                'bordereaux_er_info.T',
                'bordereaux_er_info.U',
                'bordereaux_er_info.V',
                'bordereaux_er_info.W',
                'bordereaux_er_info.X',
                'bordereaux_er_info.Y',
                'bordereaux_er_info.Z',
                'bordereaux_er_info.AA',
                'bordereaux_er_info.AB',
                'bordereaux_er_info.AC',
                'bordereaux_er_info.AD',
                'bordereaux_er_info.AE',
                'bordereaux_er_info.AF',
                'bordereaux_er_info.AG',
                'bordereaux_er_info.AH',
                'bordereaux_er_info.AI',
                'bordereaux_er_info.AJ',
                'bordereaux_er_info.AK',
                'bordereaux_er_info.AL',
                'bordereaux_er_info.AM',
                'bordereaux_er_info.AN',
                'bordereaux_er_info.AO',
                'bordereaux_er_info.AP',
                'bordereaux_er_info.AQ',
                'bordereaux_er_info.AR',
                'bordereaux_er_info.AS'
            )
            ->where('carts.product_id', 2)
            ->whereNotNull('payments.paid_at');

        if (!is_null($this->date)) {
            $payments_data->whereDate('payments.paid_at', $this->date);
        }

        $payments = $payments_data->get();

        return view('template-bordereaux-bankers-er-daily', [
            'payments' => $payments
        ]);
    }
}
