<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // per i rinnovi
        // per pubblico file WORDING BINDER NL 2021_SECTION_A_RENEW.pdf
        // per privato WORDING BINDER NL 2021_SECTION B_RENEW.pdf
        // per i nuovi
        // per pubblico WORDING BINDER NL 2021_SECTION_A
        // per privato WORDING BINDER NL 2021_SECTION B
        // $contracts = array(
        //     array(
        //         "contract_id" => 5,
        //         "Contract_filename" => "INFORMATIVO-COLPA-GRAVE-DIPSSN.PDF",
        //         "contract_prop" => NULL,
        //     ),
        //     array(
        //         "contract_id" => 6,
        //         "Contract_filename" => "GN_Lloyds_Private.pdf",
        //         "contract_prop" => NULL,
        //     ),
        //     array(
        //         "contract_id" => 9,
        //         "Contract_filename" => "GN_Lloyds_PrivateDIP-WORDING.pdf",
        //         "contract_prop" => NULL,
        //     ),
        // );

        Contract::factory()->count(4)->sequence(
            [
                'contract_filepath' => '/storage/contracts/WORDING_BINDER_NL_2021_SECTION_A_PREV2022.pdf', // medico, pubblico, rinnovo
            ],
            [
                'contract_filepath' => '/storage/contracts/WORDING_BINDER_NL_2021_SECTION_B_PREV2022.pdf' // medico, privato, rinnovo
            ],
            [
                'contract_filepath' => '/storage/contracts/WORDING_BINDER_NL_2021_SECTION_A.pdf' // medico pubblico, nuovo cliente
            ],
            [
                'contract_filepath' => '/storage/contracts/WORDING_BINDER_NL_2021_SECTION_B.pdf' // medico privato, nuovo cliente
            ],
        )->create();
    }
}
