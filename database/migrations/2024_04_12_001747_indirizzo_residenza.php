<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndirizzoResidenza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ehealth_buyers', function (Blueprint $table) {
            $table->string('indirizzo_residenza');
        });
        Schema::table('ehealth_buyers', function (Blueprint $table) {
            $table->string('citta_residenza');
        });
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
