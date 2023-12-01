<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Certificate;

class PaymentBankersToPdfService
{

    protected $cart;

    public function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for PaymentBankersToPdfService precedure");
        }
        $this->cart = $cart;
    }

    private function setCertificateDates()
    {
        //$renewal_date = (empty($this->cart->renewal_date)) ? $this->cart->payment->paid_at : $this->cart->renewal_date;

        $certificate = Certificate::find($this->cart->certificate->id);
        $dates = [
            'start_validity_date' => $certificate->start_validity_date,
            'end_validity_date' => $certificate->end_validity_date
        ];

        return $dates;
    }

    public function generateAndPersist()
    {
        $user = $this->cart->user;
        $clientDetails = $user->clientDetails;
        $product_name  = $this->cart->product->name;
        $dates = $this->setCertificateDates();
        $start_validity_date = $dates['start_validity_date']->format('d/m/Y');
        $end_validity_date = $dates['end_validity_date']->format('d/m/Y');
        $payment_date = Carbon::parse($this->cart->payment->paid_at)->format('d/m/Y');
        $rif_gol = $this->cart->payment->payment_no;


        foreach($this->cart->user->clientDetails->productClientDetails as $productClientDetail){
            if($productClientDetail->field_name == 'appartenenza'){
                $first = $productClientDetail->pivot->selected_field_value;
            }
            if($productClientDetail->field_name == 'regione_di_appartenenza'){

                $regione = $productClientDetail->pivot->selected_field_value;

                switch($regione){
                    case 1:
                        $regione = "Abruzzo";
                        break;
                    case 2:
                        $regione = "Basilicata";
                        break;
                    case 3:
                        $regione = "Calabria";
                        break;
                    case 4:
                        $regione = "Campania";
                        break;
                    case 5:
                        $regione = "Friuli Venezia Giulia";
                        break;
                    case 6:
                        $regione = "Lazio";
                        break;
                    case 7:
                        $regione = "Liguria";
                        break;
                    case 8:
                        $regione = "Lombardia";
                        break;
                    case 9:
                        $regione = "Marche";
                        break;
                    case 10:
                        $regione = "Molise";
                        break;
                    case 11:
                        $regione = "Piemonte";
                        break;
                    case 12:
                        $regione = "Puglia";
                        break;
                    case 13:
                        $regione = "Sardegna";
                        break;
                    case 14:
                        $regione = "Sicilia";
                        break;
                    case 15:
                        $regione = "Toscana";
                        break;
                    case 16:
                        $regione = "Trentino Alto Adige";
                        break;
                    case 17:
                        $regione = "Umbria";
                        break;
                    case 18:
                        $regione = "Val d'Aosta";
                        break;
                    case 19:
                        $regione = "Veneto";
                        break;
                                                                $regione = "Liguria";
                }
            }
            if($productClientDetail->field_name == 'abi'){
                $abi = $productClientDetail->pivot->selected_field_value;
            }
        }

        $gross_premium = number_format(
            $this->cart->gross_premium,
            2,
            ",",
            "."
        );
        $admin_fee = number_format(
            $this->cart->admin_fee,
            2,
            ",",
            "."
        );
        $total_payable = number_format(
            $this->cart->total_payable,
            2,
            ",",
            "."
        );
        if($this->cart->productOffer->indemnity_limit == 0){
            $cassa = "-";
        }
        else{
        $cassa = number_format(
            $this->cart->productOffer->indemnity_limit,
            2,
            ",",
            "."
        );
        $cassa = "€ ".$cassa;
        }
        if($this->cart->productOffer->indemnity_limit_patrimonial == 0){
            $rc = "-";
        }
        else{
        $rc = number_format(
            $this->cart->productOffer->indemnity_limit_patrimonial,
            2,
            ",",
            "."
        );
        $rc = "€ ".$rc;
        }
        if($this->cart->productOffer->legal_fees == 0){
            $legal_fees = "-";
        }
        else{
        $legal_fees = number_format(
            $this->cart->productOffer->legal_fees,
            2,
            ",",
            "."
        );
        $legal_fees = "€ ".$legal_fees;
        }

        if($this->cart->product_id == 2){
            $data = [
                'cert_no' => $rif_gol,
                'address' => $clientDetails->address,
                'post_code' => $clientDetails->postal_code,
                'telephone' => $clientDetails->phone,
                'city' => $clientDetails->city,
                'county' => $clientDetails->province,
                'taxcode' => $clientDetails->fiscal_code,
                'name' => $clientDetails->first_name,
                'surname' => $clientDetails->last_name,
                'payment_date' => $payment_date,
                'product_name' => $product_name,
                'start_date' => $start_validity_date,
                'end_date' => $end_validity_date,
                'rif_gol' => $rif_gol,
                'gross_premium' => $gross_premium,
                'admin_fee' => $admin_fee,
                'total_payable' => $total_payable,
                'abi' => $abi,
                'first' => $first,
                'email' => $user->email,
                'combination' => $this->cart->productOffer->combination,
                'sku' => $this->cart->productOffer->sku,
                'cassa' => $cassa,
                'rc' => $rc,
                'legal' => $legal_fees,
            ];
        }
        if($this->cart->product_id == 3){
            $data = [
                'cert_no' => $rif_gol,
                'address' => $clientDetails->address,
                'post_code' => $clientDetails->postal_code,
                'telephone' => $clientDetails->phone,
                'city' => $clientDetails->city,
                'county' => $clientDetails->province,
                'taxcode' => $clientDetails->fiscal_code,
                'name' => $clientDetails->first_name,
                'surname' => $clientDetails->last_name,
                'payment_date' => $payment_date,
                'product_name' => $product_name,
                'start_date' => $start_validity_date,
                'end_date' => $end_validity_date,
                'rif_gol' => $rif_gol,
                'gross_premium' => $gross_premium,
                'admin_fee' => $admin_fee,
                'total_payable' => $total_payable,
                'abi' => $abi,
                //'first' => $first,
                'email' => $user->email,
                'combination' => $this->cart->productOffer->combination,
                'sku' => 'ITFINC08669',
                'cassa' => $cassa,
                'rc' => $rc,
                'legal' => $legal_fees,
            ];
        }
        if($this->cart->product_id == 4){
            $data = [
                'cert_no' => $rif_gol,
                'address' => $clientDetails->address,
                'post_code' => $clientDetails->postal_code,
                'telephone' => $clientDetails->phone,
                'city' => $clientDetails->city,
                'county' => $clientDetails->province,
                'taxcode' => $clientDetails->fiscal_code,
                'name' => $clientDetails->first_name,
                'surname' => $clientDetails->last_name,
                'payment_date' => $payment_date,
                'product_name' => $product_name,
                'start_date' => $start_validity_date,
                'end_date' => $end_validity_date,
                'rif_gol' => $rif_gol,
                'gross_premium' => $gross_premium,
                'admin_fee' => $admin_fee,
                'total_payable' => $total_payable,
                'abi' => $abi,
                'first' => $regione,
                'email' => $user->email,
                'combination' => $this->cart->productOffer->combination,
                'sku' => 'ITFINC08774',
                'cassa' => $cassa,
                'rc' => $rc,
                'legal' => $legal_fees,
            ];
        }

        PDF::setOptions([
            // 'dpi' => 150,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultPaperSize' => 'a4', // Set the default paper size to A4
        ]);

        if($this->cart->product_id == 2){
            $pdf = PDF::loadView('template-certificate-bankers-ER', $data);
        }
        if($this->cart->product_id == 3){
            $pdf = PDF::loadView('template-certificate-bankers-SA', $data);
        }
        if($this->cart->product_id == 4){
            $pdf = PDF::loadView('template-certificate-bankers-RI', $data);
        }
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/certificate-final.pdf', $pdf->stream());
        return $policy_response_folder . '/certificate-final.pdf';
    }
}
