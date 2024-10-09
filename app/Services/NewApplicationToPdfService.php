<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SoapFault;

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

        if($this->cart->product->id == 1){
            $authority = $clientDetails->getConditionalFieldMatchById(3, false);
            $authority_type = $clientDetails->getConditionalFieldMatchById(2);
        }
        else{
            $authority = "nulla";
            $authority_type = "nulla";
        }
        $authority_type_public_flag = ($authority_type == 'Pubblico') ? true : false;
        $title = $this->getTitle($authority_type_public_flag);

        if($this->cart->product->id == 1){
            $specialization = $clientDetails->getConditionalFieldMatchById(4, false);
            $profession = $clientDetails->getConditionalFieldMatchById(1);
        }
        else if($this->cart->product->id == 5){
            $title = "Polizza Responsabilità Civile Professionale Giovani Medici
            (Medici Abilitati Tirocinanti E Specializzandi) di età inferiore a 38 anni";
            $specialization = $clientDetails->getConditionalFieldMatchById(11, false);
            $profession = $clientDetails->getConditionalFieldMatchById(10);
        }
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

        $stringaData = str_replace("-", "/", $this->cart->giorno_completamento_step_1);  // Formato: DD/MM/YYYY
        //dd($stringaData);
        $dataCarbon = Carbon::createFromFormat('Y/m/d', $stringaData)->format('d/m/Y');

        echo $dataCarbon;  // Output: 2024-10-09 00:00:00

        if(isset($this->cart->renewal_date)){
            $data = [
                'authority_type_public_flag' => $authority_type_public_flag,
                'data_modulo_proposta' => $dataCarbon,
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
                'retroactivity' => $this->cart->indemnity_limit_patrimonial,
            ];
        }
        else{
            $data = [
                'authority_type_public_flag' => $authority_type_public_flag,
                'data_modulo_proposta' => $dataCarbon,
                'title' => $title,
                'fullname' => $clientDetails->last_name . ' ' . $clientDetails->first_name,
                'fiscal_code' => $clientDetails->fiscal_code,
                'profession' => $profession,
                'specialization' => $specialization,
                'authority' => $authority,
                'authority_type' => $authority_type,
                'address' => $clientDetails->address . ' ' . $clientDetails->postal_code . ' - ' . $clientDetails->city . ' (' . $clientDetails->province . ')',
                'email' => $user->email,
                'phone' => $clientDetails->phone,
                'indemnity_limit' => $indemnity_limit,
                'indemnity_limit_patrimonial' => $indemnity_limit_patrimonial,
                'insurance_deductible' => $insurance_deductible,
                'data_generazione' => Carbon::now()->format('d-m-Y'),
                'data_effetto' => Carbon::now()->format('d-m-Y'),
                'retroactivity' => $this->cart->indemnity_limit_patrimonial,
            ];
        }
        

        PDF::setOptions([
            // 'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'fontHeightRatio' => 1,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true
        ]);


        if($this->cart->product->id == 1){
            $pdf = PDF::loadView('template-new-application', $data);
            $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
            Storage::disk('private')->put($policy_response_folder . '/new-application.pdf', $pdf->stream());
            return $policy_response_folder . '/new-application.pdf';
        }
        else if($this->cart->product->id == 5){
            $pdf = PDF::loadView('template-new-application-giovane-medico', $data);
            $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
            Storage::disk('private')->put($policy_response_folder . '/new-application.pdf', $pdf->stream());
            return $policy_response_folder . '/new-application.pdf';
        }
    }

    private function getTitle($authority_type_public_flag)
    {
        $title = "Responsabilità Civile Personale Dipendente";
        if ($authority_type_public_flag) {
            $title .= " del S.S.N.";
        } else {
            $title .= "/Collaboratore Privato";
        }
        return $title;
    }
}
