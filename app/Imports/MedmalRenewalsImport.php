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

class MedmalRenewalsImport implements OnEachRow, WithStartRow, WithCustomCsvSettings, WithBatchInserts //, WithUpserts
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

        $renewal_date = $row[0];
        $contact_ref = $row[1];
        $version_ref = $row[2];
        $client_surname = ucwords($row[3]);
        $client_first_name = ucwords($row[4]);
        $specialization = $row[5];
        $primary_address_line_1 = $row[6];
        $primary_city = $row[7];
        $primary_county = $row[8]; // provincia
        $primary_postcode = $row[9];
        $primary_contact_email = $row[10];
        $primary_contact_mobile = $row[11];
        $primary_contact_dob = Carbon::createFromFormat('d/m/Y', $row[12])->format('Y-m-d'); // data di nascita
        $policy_description = $row[13];
        $fiscal_code = $row[14];
        $flag_accident = (empty($row[15])) ? 0 : 1;
        $type = $row[16]; // professione medici, tipo "personale sanitario ecc"
        $section = $row[17]; // ente di appartenenza, "pubblico" o "privato"
        $ente = $row[18]; // ente
        $retroactivity = $row[19];
        $flag_prev_2022 = $row[20];
        $product_offer_id = $row[21];
        $gender = $row[22];

        $user = User::create([
            'account_id' => 1, // Golinucci
            'first_name'     => $client_first_name,
            'last_name'     => $client_surname,
            'email'    => $primary_contact_email,
            'password' => Hash::make('testtest'),
        ]);
        setPermissionsTeamId(1);
        $user->assignRole(Role::ROLE_USER);
        $clientDetails = ClientDetail::create([
            'user_id' => $user->id,
            'first_name' => $client_first_name,
            'last_name' => $client_surname,
            'fiscal_code' => $fiscal_code,
            'gender' => $gender,
            'dob' => $primary_contact_dob,
            'address' => $primary_address_line_1,
            'city' => $primary_city,
            'province' => $primary_county,
            'country' => 'IT',
            'postal_code' => $primary_postcode,
            'phone' => $primary_contact_mobile,
            'contact_ref' => $contact_ref,
            'version_ref' => $version_ref,
            'note' => '',
            'with_accident' => $flag_accident,
            'flag_prev_2022' => $flag_prev_2022
        ]);

        $productOffer = ProductOffer::find($product_offer_id);
        $product = $productOffer->product;
        $productOfferConditions = $productOffer->conditionalFields;
        // ProductClientDetail
        foreach ($product->conditionalFields as $conditionalField) {
            switch ($conditionalField->field_name) {
                case 'qualification':
                    $selected_field_value = $productOfferConditions->firstWhere(
                        'id',
                        $conditionalField->id
                    );
                    $selected_field_value = $selected_field_value->pivot->selected_field_value;
                    ProductClientDetail::create([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $selected_field_value
                    ]);
                    break;
                case 'authority_type':
                    $selected_field_value = $productOfferConditions->firstWhere(
                        'id',
                        $conditionalField->id
                    );
                    $selected_field_value = $selected_field_value->pivot->selected_field_value;
                    ProductClientDetail::create([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $selected_field_value
                    ]);
                    break;
                case 'authority':
                    ProductClientDetail::create([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $ente
                    ]);
                    break;
                case 'specialization':
                    ProductClientDetail::create([
                        'client_detail_id' => $clientDetails->id,
                        'conditional_field_id' => $conditionalField->id,
                        'selected_field_value' => $specialization
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
            'renewal_date' => Carbon::createFromFormat('d/m/Y', $renewal_date)->format('Y-m-d'),
            'retroactivity' => Carbon::createFromFormat('d/m/Y', $retroactivity)->format('Y-m-d'),
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
