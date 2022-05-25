<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DatasheetToPdfService
{

    protected $cart;

    public function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for DatasheetToPdfService precedure");
        }
        $this->cart = $cart;
    }

    public function generateAndPersist($profession)
    {
        $user = $this->cart->user;
        $clientDetails = $user->clientDetails;
        $certificate = $this->cart->certificate;
        // TODO non serve?
        //$authority = $clientDetails->getConditionalFieldMatchById(3, false);
        $specialization = $clientDetails->getConditionalFieldMatchById(4, false);
        $retroactivity = $certificate->getRetroactivity();
        $indemnity_limit = number_format(
            $this->cart->indemnity_limit,
            2,
            ",",
            "."
        );
        $indemnity_limit_patrimonial = number_format(
            $this->cart->indemnity_limit_patrimonial,
            2,
            ",",
            "."
        );
        $start_validity_date = $certificate->start_validity_date->toDateString();
        $end_validity_date = $certificate->end_validity_date->toDateString();
        $insurance_deductible = '0'; // franchigia
        $rif_gol = $certificate->certificate_no;
        //= Product::where('id', $this->cart->product_id)->first();
        $data = [
            'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
            'profession_and_specializzation' => $profession . '<br>' . $specialization,
            'address' => $clientDetails->address . '<br>' . $clientDetails->postal_code . ' - ' . $clientDetails->city . ' (' . $clientDetails->province . ')',
            'insurance_period' => "Dal $start_validity_date al $end_validity_date",
            'retroactivity' => $retroactivity,
            'indemnity_limit' => $indemnity_limit,
            'indemnity_limit_patrimonial' => $indemnity_limit_patrimonial,
            'insurance_deductible' => $insurance_deductible,
            'rif_gol' => $rif_gol,
        ];

        PDF::setOptions([
            // 'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'fontHeightRatio' => 1,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $pdf = PDF::loadView('template-datasheet', $data);
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/scheda.pdf', $pdf->stream());
        return $policy_response_folder . '/scheda.pdf';
    }

}
