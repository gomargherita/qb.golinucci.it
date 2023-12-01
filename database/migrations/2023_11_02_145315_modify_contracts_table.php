<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_IPID RC PROFESSIONALE COLPA GRAVE STRUTTURA PUBBLICA  - SECTION A2204AUH4253-LB.pdf' WHERE id = 1");
        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_IPID RC PROFESSIONALE COLPA GRAVE STRUTTURA PUBBLICA  - SECTION A2204AUH4253-LB.pdf' WHERE id = 3");

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
