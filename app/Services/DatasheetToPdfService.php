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

        if($this->cart->product->id == 1){
            $specialization = $clientDetails->getConditionalFieldMatchById(4, false);
            $qualification = "";
        }
        else if($this->cart->product->id == 5){
            $specialization = $clientDetails->getConditionalFieldMatchById(11, false);
            $qualificationRaw = $clientDetails->getConditionalFieldMatchById(10, false);

            switch($qualificationRaw){
                case 1:
                    $qualification = "Medico generico (medico abilitato)";
                    break;
                case 2:
                    $qualification = "Medico in formazione specialistica";
                    break;
                case 3:
                    $qualification = "Medico in formazione specifica in medicina generale";
                    break;
            }
        }
        $retroactivity = $certificate->retroactivity->format('d/m/Y');
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
        $start_validity_date = $certificate->start_validity_date->format('d/m/Y');
        $end_validity_date = $certificate->end_validity_date->format('d/m/Y');
        $insurance_deductible = '0'; // franchigia
        $rif_gol = $this->cart->payment->payment_no;
        //= Product::where('id', $this->cart->product_id)->first();
        $data = [
            'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
            'profession' => $profession,
            'specialization' => $specialization,
            'qualification' => $qualification,
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

        if($this->cart->product->id == 1){
            $pdf = PDF::loadView('template-datasheet', $data);
        }
        else if($this->cart->product->id == 5){
            $pdf = PDF::loadView('template-datasheet-gmedici', $data);            
        }
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/scheda.pdf', $pdf->stream());
        return $policy_response_folder . '/scheda.pdf';
    }

}
