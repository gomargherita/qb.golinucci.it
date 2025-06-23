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
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\map;

class BdxMedmalPaymentsExportMensile implements FromView, WithHeadingRow
{
    use Exportable;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    // public function headings(): array
    // {
    //     return [
    //         'NOME',
    //         'COGNOME',
    //         'CODICE FISCALE',
    //         'INDIRIZZO',
    //         'CAP',
    //         'CITTA\'',
    //         'PROVINCIA',
    //         'NAZIONE',
    //         'EMAIL',
    //         'TELEFONO UFFICIO', // fisso 0
    //         'TELEFONO CELLULARE',
    //         'PARTITAIVA', // vuoto
    //         'TIPOATTIVITA',
    //         'START DATE',
    //         'END DATE',
    //         'ACCOUNT EXECUTIVE', // fisso Paolo Golinucci
    //         'ACCOUNT HANDLER', // fisso Paolo Golinucci
    //         'POLICY NUMBER', // num polizza Lloyds (meglio) oppure  se non riesco quello contabile
    //         'PREMIUM (EXCL. TAX)', // formattato con virgola, tipo 40,7
    //         'TAX',
    //         'COMMISSION',
    //         'ADMIN FEE',
    //         'TOTAL PREMIUM + TAX + ADMIN FEE',
    //         'INVOICE NUMBER',
    //         'INTRODUCER', // fisso 50464394 per pubblico, fisso 50464460 per privato SPECIFICA MODIFICATA IL 4 Luglio
    //         'INTRODUCER 2', // vuoto
    //         'INSURER', // fisso Lloyd's
    //         'INSURER ACCOUNT', // fisso Carpenter Colpa Grave
    //         'POLICY DESCRIPTION', // come INVOICE NUMBER
    //         'CONTACT REF',
    //         'CONTACT NOTES', // specializzazione
    //         'DATA DI NASCITA', // formato giorno/mese/anno
    //         'BUSINESS EVENT', // fisso New business
    //         'VERSION REF',
    //         'REMARKETED' // ?
    //         'PAYMENT DATE' // notificato il 22/6/22
    //     ];
    // }

    public function view(): View
    {
        $payments_data = DB::table('payments')
            ->join('carts', 'payments.cart_id', '=', 'carts.id')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('certificates', 'carts.id', '=', 'certificates.cart_id', function($join) {
                $join->where('certificates.deleted_at', null);
            })
            ->join('users', 'carts.user_id', '=', 'users.id')
            ->join('client_details', 'users.id', '=', 'client_details.user_id')
            ->leftJoin('product_client_details as pcd1', function($join) {
                $join->on('client_details.id', '=', 'pcd1.client_detail_id');
                $join->where('pcd1.conditional_field_id', 1);
            })
            ->leftJoin('product_client_details as pcd2', function($join) {
                $join->on('client_details.id', '=', 'pcd2.client_detail_id');
                $join->where('pcd2.conditional_field_id', 4);
            })
            ->leftJoin('product_client_details as pcd3', function($join) {
                $join->on('client_details.id', '=', 'pcd3.client_detail_id');
                $join->where('pcd3.conditional_field_id', 2);
            })
            ->leftJoin('product_client_details as pcd4', function($join) {
                $join->on('client_details.id', '=', 'pcd4.client_detail_id');
                $join->where('pcd4.conditional_field_id', 3);
            })
            ->leftJoin('product_client_details as pcd5', function($join) {
                $join->on('client_details.id', '=', 'pcd5.client_detail_id');
                $join->where('pcd5.conditional_field_id', 4);
            })
            ->leftJoin('product_client_details as qualificaGM', function($join) {
                $join->on('client_details.id', '=', 'qualificaGM.client_detail_id');
                $join->where('qualificaGM.conditional_field_id', 10);
            })
            ->leftJoin('product_client_details as specialGM', function($join) {
                $join->on('client_details.id', '=', 'specialGM.client_detail_id');
                $join->where('specialGM.conditional_field_id', 11);
            })
            ->select(
                'carts.product_id as pid',
                'client_details.first_name',
                'client_details.last_name',
                'client_details.fiscal_code',
                'client_details.motivo_blocco_invio',
                'client_details.address',
                'client_details.postal_code',
                'client_details.city',
                'client_details.province',
                'client_details.country',
                'users.email',
                'client_details.phone',
                'pcd1.selected_field_value as tipoattivita',
                'carts.renewal_date', // if renewal_date null uso payment_at // 'certificates.start_validity_date',
                // 'certificates.end_validity_date',
                'certificates.doprint_certificate_no',
                'certificates.retroactivity',
                'certificates.created_at',
                'carts.net_plus_access_premium',
                'carts.net_premium',
                'carts.tax',
                'carts.accessory',
                'carts.indemnity_limit',
                'carts.admin_fee',
                'payments.total_payable',
                'payments.payment_no',
                'payments.paid_at',
                'client_details.contact_ref',
                'client_details.version_ref',
                'pcd2.selected_field_value as contactnotes',// contact notes da prendere da product_client_details
                'pcd3.selected_field_value as authority_type',
                'pcd4.selected_field_value as authority',
                'pcd5.selected_field_value as specialization',
                'qualificaGM.selected_field_value as qualificaGM',
                'specialGM.selected_field_value as specialGM',
                'client_details.dob',
            )
            ->whereIn('carts.product_id', [1, 5])
            ->whereNotNull('payments.paid_at');

        if (!is_null($this->date)) {
            //Log::info(date('d/m/Y', strtotime($this->date)));
            // Ottieni l'anno e il mese dalla data specificata
            $year = date('Y', strtotime($this->date));
            $month = date('m', strtotime($this->date));

            // Filtra i pagamenti per l'anno e il mese specificati
            $payments_data = $payments_data->whereYear('payments.paid_at', $year)->whereMonth('payments.paid_at', $month);
            //$payments_data->where('payments.paid_at', $this->date);
        }

        $payments = $payments_data->get();

        $i = 0;
        for($i=0; $i<count($payments); $i++){
            $payments[$i]->payment_no = str_replace('-', '/', $payments[$i]->payment_no);
        }
        

        return view('template-bordereaux-mensile', [
            'payments' => $payments
        ]);
    }
}
