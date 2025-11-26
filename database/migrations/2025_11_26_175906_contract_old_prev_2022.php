<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ContractOldPrev2022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE contracts ADD old_prev_2022 varchar(255) DEFAULT null");

        DB::statement("UPDATE contracts SET contract_prev_2022 = '/storage/contracts/WORDING_BINDER_NL_2025_SECTION_A_€_1.000.000.pdf' WHERE id = 11");
        DB::statement("UPDATE contracts SET old_prev_2022 = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_1.000.000.pdf' WHERE id = 11");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_500.000.pdf' WHERE id = 11");
        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/WORDING_BINDER_NL_2025_SECTION_A_€_500.000.pdf' WHERE id = 11");

        DB::statement("UPDATE contracts SET contract_prev_2022 = '/storage/contracts/WORDING_BINDER_NL_2025_SECTION_B_€_1.000.000.pdf' WHERE id = 12");
        DB::statement("UPDATE contracts SET old_prev_2022 = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_1.000.000.pdf' WHERE id = 12");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_500.000.pdf' WHERE id = 12");
        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/WORDING_BINDER_NL_2025_SECTION_B_€_500.000.pdf' WHERE id = 12");

        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/WORDING_GIOVANE_MEDICO_2025.pdf' WHERE id = 8");
        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/WORDING_GIOVANE_MEDICO_2025.pdf' WHERE id = 13");

        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/AGGIORNATO_WORDING_GIOVANE_MEDICO_2024.pdf' WHERE id = 8");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/AGGIORNATO_WORDING_GIOVANE_MEDICO_2024.pdf' WHERE id = 13");
        
        DB::statement("UPDATE contracts SET contract_old = '' WHERE id = 1");
        DB::statement("UPDATE contracts SET contract_filepath = '' WHERE id = 1");
        DB::statement("UPDATE contracts SET contract_old = '' WHERE id = 2");
        DB::statement("UPDATE contracts SET contract_filepath = '' WHERE id = 2");
        DB::statement("UPDATE contracts SET contract_old = '' WHERE id = 3");
        DB::statement("UPDATE contracts SET contract_filepath = '' WHERE id = 3");
        DB::statement("UPDATE contracts SET contract_old = '' WHERE id = 4");
        DB::statement("UPDATE contracts SET contract_filepath = '' WHERE id = 4");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
