<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaymentCertificateToPdfService
{

    protected $cart;

    public function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for PaymentCertificateToPdfService precedure");
        }
        $this->cart = $cart;
    }

    public function generateAndPersist()
    {
        $user = $this->cart->user;
        $clientDetails = $user->clientDetails;
        $certificate = $this->cart->certificate;
        $product_name  = $this->cart->product->name;
        $start_validity_date = $certificate->start_validity_date->format('d/m/Y');
        $end_validity_date = $certificate->end_validity_date->format('d/m/Y');
        $payment_date = Carbon::parse($this->cart->payment->paid_at)->format('d/m/Y');
        $rif_gol = $certificate->certificate_no;
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
        $data = [
            'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
            'client_details' => $clientDetails->address . '<br>' . $clientDetails->postal_code . ' - ' . $clientDetails->city . ' (' . $clientDetails->province . ')<br>' . $clientDetails->fiscal_code,
            'payment_date' => $payment_date,
            'product_name' => $product_name,
            'insurance_period' => "$start_validity_date / $end_validity_date",
            'rif_gol' => $rif_gol,
            'gross_premium' => $gross_premium,
            'admin_fee' => $admin_fee,
            'total_payable' => $total_payable
        ];

        PDF::setOptions([
            // 'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'fontHeightRatio' => 1,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $pdf = PDF::loadView('template-payment-certificate', $data);
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/payment-certificate.pdf', $pdf->stream());
        return $policy_response_folder . '/payment-certificate.pdf';
    }

}
