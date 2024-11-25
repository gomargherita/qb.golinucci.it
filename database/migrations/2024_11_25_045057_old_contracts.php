<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class OldContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE contracts ADD contract_old varchar(255) DEFAULT null");

        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_1.000.000.pdf' WHERE id = 1");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/WORDING_BINDER_NL_2023_SECTION_A_PREV2022.pdf' WHERE id = 1");

        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_1.000.000.pdf' WHERE id = 2");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/WORDING_BINDER_NL_2023_SECTION_B_PREV2022.pdf' WHERE id = 2");

        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/WORDING_GIOVANE_MEDICO_2024.pdf' WHERE id = 8");
        DB::statement("UPDATE contracts SET contract_old = '/storage/contracts/WORDING_GIOVANE_MEDICO_2024.pdf' WHERE id = 8");
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
