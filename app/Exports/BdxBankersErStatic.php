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

class BdxBankersErStatic implements FromView, WithHeadingRow
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
            ->join('product_offers', 'carts.product_offer_id', '=', 'product_offers.id')
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
                'product_offers.combination',
                'payments.payment_gateway_id',
                'payments.total_payable',
                'payments.paid_at',
                'carts.renewal_date', // if renewal_date null uso payment_at // 'certificates.start_validity_date',
                // 'certificates.end_validity_date',
                //END DATE
                'certificates.end_validity_date',
                'bordereaux_er_info.italiana_cassa',
                'bordereaux_er_info.italiana_patrimoniale',
                'bordereaux_er_info.chubb_patrimoniale',
                'bordereaux_er_info.sara_patrimoniale',
                'bordereaux_er_info.total_premium',
                'bordereaux_er_info.fee',
                'bordereaux_er_info.massimale_cassieri',
                'bordereaux_er_info.massimale_patrimoniale'
            )
            ->where('carts.product_id', 2)
            ->whereNotNull('payments.paid_at');

        if (!is_null($this->date)) {
            $payments_data->whereDate('payments.paid_at', $this->date);
        }

        $payments = $payments_data->get();

        return view('template-bordereaux-bankers-er-static', [
            'payments' => $payments
        ]);
    }
}
