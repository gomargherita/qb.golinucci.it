<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class NewApplicationToPdfService
{

    protected $cart;

    public function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for NewApplicationToPdfService precedure");
        }
        $this->cart = $cart;
    }

    public function generateAndPersist()
    {
        $user = $this->cart->user;
        $clientDetails = $user->clientDetails;
        $certificate = $this->cart->certificate;
        $authority = $clientDetails->getConditionalFieldMatchById(3, false);
        $authority_type = $clientDetails->getConditionalFieldMatchById(2);
        $authority_type_public_flag = ($authority_type == 'Pubblico') ? true : false;
        $title = $this->getTitle($authority_type_public_flag);
        $specialization = $clientDetails->getConditionalFieldMatchById(4, false);
        $profession = $clientDetails->getConditionalFieldMatchById(1);
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
        $insurance_deductible = '0'; // franchigia
        //= Product::where('id', $this->cart->product_id)->first();
        if(isset($this->cart->renewal_date)){
            $data = [
                'authority_type_public_flag' => $authority_type_public_flag,
                'title' => $title,
                'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
                'fiscal_code' => $clientDetails->fiscal_code,
                'profession' => $profession,
                'specialization' => $specialization,
                'authority' => $authority,
                'authority_type' => $authority_type,
                'address' => $clientDetails->address . '' . $clientDetails->postal_code . ' - ' . $clientDetails->city . ' (' . $clientDetails->province . ')',
                'email' => $user->email,
                'phone' => $clientDetails->phone,
                'indemnity_limit' => $indemnity_limit,
                'indemnity_limit_patrimonial' => $indemnity_limit_patrimonial,
                'insurance_deductible' => $insurance_deductible,
                'data_generazione' => Carbon::now()->format('d-m-Y'),
                'data_effetto' => $this->cart->renewal_date->format('d-m-Y'),
            ];
        }
        else{
            $data = [
                'authority_type_public_flag' => $authority_type_public_flag,
                'title' => $title,
                'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
                'fiscal_code' => $clientDetails->fiscal_code,
                'profession' => $profession,
                'specialization' => $specialization,
                'authority' => $authority,
                'authority_type' => $authority_type,
                'address' => $clientDetails->address . '' . $clientDetails->postal_code . ' - ' . $clientDetails->city . ' (' . $clientDetails->province . ')',
                'email' => $user->email,
                'phone' => $clientDetails->phone,
                'indemnity_limit' => $indemnity_limit,
                'indemnity_limit_patrimonial' => $indemnity_limit_patrimonial,
                'insurance_deductible' => $insurance_deductible,
                'data_generazione' => Carbon::now()->format('d-m-Y'),
                'data_effetto' => Carbon::now()->format('d-m-Y'),
            ];
        }
        

        PDF::setOptions([
            // 'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'fontHeightRatio' => 1,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $pdf = PDF::loadView('template-new-application', $data);
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/new-application.pdf', $pdf->stream());
        return $policy_response_folder . '/new-application.pdf';
    }

    private function getTitle($authority_type_public_flag)
    {
        $title = "Responsabilità Civile Personale Dipendente";
        if ($authority_type_public_flag) {
            $title .= " del S.N.N.";
        } else {
            $title .= "/Collaboratore Privato";
        }
        return $title;
    }
}
