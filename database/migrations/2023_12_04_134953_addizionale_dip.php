<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddizionaleDip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('dip_aggiuntivo');
        });

        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION A - 20232311AVN2634-LB.docx.pdf' WHERE id = 1");
        DB::statement("UPDATE contracts SET dip_aggiuntivo = '/storage/contracts/DIP414_dip aggiuntivo section a2311AVN2553-LB.docx.pdf' WHERE id = 1");
        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION A - 20232311AVN2634-LB.docx.pdf' WHERE id = 3");
        DB::statement("UPDATE contracts SET dip_aggiuntivo = '/storage/contracts/DIP414_dip aggiuntivo section a2311AVN2553-LB.docx.pdf' WHERE id = 3");


        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION B - 20232311AVN2648-LB.docx.pdf' WHERE id = 2");
        DB::statement("UPDATE contracts SET dip_aggiuntivo = '/storage/contracts/DIP414_dip aggiuntivo section b-20232311AVN2608-LB.docx.pdf' WHERE id = 2");
        DB::statement("UPDATE contracts SET dip = '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION B - 20232311AVN2648-LB.docx.pdf' WHERE id = 4");
        DB::statement("UPDATE contracts SET dip_aggiuntivo = '/storage/contracts/DIP414_dip aggiuntivo section b-20232311AVN2608-LB.docx.pdf' WHERE id = 4");

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
