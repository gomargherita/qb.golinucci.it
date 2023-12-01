<?php

namespace App\Imports;

use App\Models\Cart;
use App\Models\ClientDetail;
use App\Models\ProductClientDetail;
use App\Models\ProductOffer;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Row;
use Exception;

class BankersRenewalsImport implements OnEachRow, WithStartRow, WithCustomCsvSettings, WithBatchInserts //, WithUpserts
{
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }

    public function batchSize(): int
    {
        return 10;
    }

    // https://docs.laravel-excel.com/3.1/imports/model.html
    // public function uniqueBy()
    // {
    //     return 'email';
    // }

    /**
     * @param Row $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function onRow(Row $row)
    {

        $rowIndex = $row->getIndex();
        $row = $row->toArray();
        $product_offer_id = $row[0];
        $client_first_name = ucwords($row[1]);
        $client_surname = ucwords($row[2]);
        $fiscal_code = $row[3];
        $primary_contact_email = $row[4];
        $primary_address_line_1 = $row[5];
        $primary_city = $row[6];
        $primary_county = $row[7]; // provincia
        $primary_postcode = $row[8];
        $office_number = $row[9];
        $primary_contact_mobile = $row[10];
        $first = $row[11];
        $bank = $row[12];
        $abi = $row[13];
        $renewal_date = $row[14];        
        //$product_offer_id = $row[15];
        //$product_offer_id = 90;

        try{
            $user = User::create([
                'account_id' => 1, // Golinucci
                'first_name'     => $client_first_name,
                'last_name'     => $client_surname,
                'email'    => $primary_contact_email,
                'password' => Hash::make('testtest'),
            ]);
        }
        catch(Exception $e){
            error_log($primary_contact_email);
            $user = User::where('email', $primary_contact_email)->first();
        }

        if(substr($primary_contact_mobile, 0, 3) != "+39" && $primary_contact_mobile != 0){
            if(strlen($primary_contact_mobile) >= 9){
                if(substr($primary_contact_mobile, 0, 2) == "39"){
                $primary_contact_mobile = "+".$primary_contact_mobile;
                }
            }else{
                $primary_contact_mobile = "+39".$primary_contact_mobile;
            }
        }

        setPermissionsTeamId(1);
        $user->assignRole(Role::ROLE_USER);
        try{
            $clientDetails = ClientDetail::firstOrCreate([
                'user_id' => $user->id,
                'first_name' => $client_first_name,
                'last_name' => $client_surname,
                'fiscal_code' => $fiscal_code,
                //'gender' => $gender,
                //'dob' => $primary_contact_dob,
                'address' => $primary_address_line_1,
                'city' => $primary_city,
                'province' => substr($primary_county, 0, 2),
                'country' => 'IT',
                'postal_code' => substr($primary_postcode, 0, 5),
                'phone' => $primary_contact_mobile,
                //'contact_ref' => $contact_ref,
                //'version_ref' => $version_ref,
                'note' => '',
                //'with_accident' => $flag_accident,
                //'flag_prev_2022' => $flag_prev_2022
            ]);

        }
        catch(Exception $e){
                $clientDetails = ClientDetail::firstOrCreate([
                    'user_id' => $user->id,
                    'first_name' => $client_first_name,
                    'last_name' => $client_surname,
                    'fiscal_code' => $fiscal_code,
                    //'gender' => $gender,
                    //'dob' => $primary_contact_dob,
                    'address' => $primary_address_line_1,
                    'city' => $primary_city,
                    'province' => substr($primary_county, 0, 2),
                    'country' => 'IT',
                    'postal_code' => substr($primary_postcode, 0, 5),
                    'phone' => 0,
                    //'contact_ref' => $contact_ref,
                    //'version_ref' => $version_ref,
                    'note' => '',
                    //'with_accident' => $flag_accident,
                    //'flag_prev_2022' => $flag_prev_2022
                ]);
        }

        $productOffer = ProductOffer::find($product_offer_id);
        $product = $productOffer->product;
        $productOfferConditions = $productOffer->conditionalFields;
        // ProductClientDetail
        foreach ($product->conditionalFields as $conditionalField) {
            switch ($conditionalField->field_name) {
                case 'telefono_ufficio':
                    /*$selected_field_value = $productOfferConditions->firstWhere(
                        'id',
                        $conditionalField->id
                    );*/
                    ProductClientDetail::firstOrCreate([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $office_number,
                        'product_id' => $product->id,
                    ]);

                    break;
                case 'appartenenza':
                    if($product->id == 4){
                        break;
                    }
                    /*$selected_field_value = $productOfferConditions->firstWhere(
                        'id',
                        $conditionalField->id
                    );*/
                    ProductClientDetail::firstOrCreate([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $first,
                        'product_id' => $product->id,
                    ]);
                    break;
                case 'banca':
                    ProductClientDetail::firstOrCreate([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $bank,
                        'product_id' => $product->id,
                    ]);
                    break;
                case 'abi':
                    ProductClientDetail::firstOrCreate([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $abi,
                        'product_id' => $product->id,
                    ]);
                    break;
            }
        }

        // Cart
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_step_id' => 1,
            'product_offer_id' => $product_offer_id,
            'cart_ref' => uniqid(),
            'renewal_date' => Carbon::createFromFormat('Y-m-d', $renewal_date)->format('Y-m-d'),
            'retroactivity' => Carbon::createFromFormat('d/m/Y', "01/01/1970")->format('Y-m-d'),
            'total_payable' => $productOffer->total_payable,
            'admin_fee' => $productOffer->admin_fee,
            'gross_premium' => $productOffer->gross_premium,
            'tax' => $productOffer->tax,
            'net_premium' => $productOffer->net_premium,
            'accessory' => $productOffer->accessory,
            'net_plus_access_premium' => $productOffer->net_plus_access_premium,
            'indemnity_limit' => $productOffer->indemnity_limit,
            'indemnity_limit_patrimonial' => $productOffer->indemnity_limit_patrimonial,
            'locked' => 'N',
            'expired_at' => Carbon::now()->addMonth(1),

        ]);
        return $user;
    }
}
