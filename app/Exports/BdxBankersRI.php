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

class BdxBankersRI implements FromView, WithHeadingRow
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
            ->leftJoin('bordereaux_er_static', 'bordereaux_er_static.product_offer_id', "=", "carts.product_offer_id")
            
            ->leftJoin('product_client_details as pcd5', function($join) {
                $join->on('client_details.id', '=', 'pcd5.client_detail_id');
                $join->where('pcd5.conditional_field_id', 5);
            })
            
            ->leftJoin('product_client_details as pcd7', function($join) {
                $join->on('client_details.id', '=', 'pcd7.client_detail_id');
                $join->where('pcd7.conditional_field_id', 7);
            })
            ->leftJoin('product_client_details as pcd8', function($join) {
                $join->on('client_details.id', '=', 'pcd8.client_detail_id');
                $join->where('pcd8.conditional_field_id', 8);
            })
            ->leftJoin('product_client_details as pcd9', function($join) {
                $join->on('client_details.id', '=', 'pcd9.client_detail_id');
                $join->where('pcd9.conditional_field_id', 9);
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
                'pcd7.selected_field_value as bank',
                'pcd8.selected_field_value as abi',
                'pcd9.selected_field_value as regione',
                'payments.payment_no',
                'product_offers.combination',
                'product_offers.gross_premium',
                'product_offers.admin_fee',
                'payments.payment_gateway_id',
                'payments.total_payable',
                'payments.paid_at',
                'carts.renewal_date', // if renewal_date null uso payment_at // 'certificates.start_validity_date',
                // 'certificates.end_validity_date',
                //END DATE
                'certificates.end_validity_date',
                'bordereaux_er_static.I',
                'bordereaux_er_static.J',
                'bordereaux_er_static.K',
                'bordereaux_er_static.L',
                'bordereaux_er_static.M',
                'bordereaux_er_static.N',
                'bordereaux_er_static.P',
            )
            ->where('carts.product_id', 4)
            ->whereNotNull('payments.paid_at');

        if (!is_null($this->date)) {
            $payments_data->whereDate('payments.paid_at', $this->date);
        }

        $payments = $payments_data->get();

        return view('template-bordereaux-bankers-RI', [
            'payments' => $payments
        ]);
    }
}
