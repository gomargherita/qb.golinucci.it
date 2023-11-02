<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyContractsTableB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE contracts SET dip = '/storage/contracts/dip - section b.pdf' WHERE id = 2");
        DB::statement("UPDATE contracts SET dip = '/storage/contracts/dip - section b.pdf' WHERE id = 4");

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
