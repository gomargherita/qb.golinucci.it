<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixPrev2022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE contracts ADD contract_prev_2022 varchar(255) DEFAULT null");

        DB::statement("UPDATE contracts SET contract_prev_2022 = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_1.000.000.pdf' WHERE id = 11");

        DB::statement("UPDATE contracts SET contract_prev_2022 = '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_1.000.000.pdf' WHERE id = 12");
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
