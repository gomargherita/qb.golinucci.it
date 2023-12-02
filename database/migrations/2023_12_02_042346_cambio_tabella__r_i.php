<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CambioTabellaRI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offer_tables SET table_elements = '{\"1\":\"AMMANCHI CASSA - MASSIMALE PER SINISTRO E PER ANNO\",\"2\":\"R.C. PATRIMONIALE MASSIMALE PER SINISTRO E PER ANNO\",\"3\":\"SPESE LEGALI MASSIMALE PER SINISTRO E PER ANNO (1)  \",\"4\":\"COSTO PER COPERTURA FINO AL 31-12-2024\", \"5\":\"COSTO PER 7 MESI FISSI DALLA DATA DI ADESIONE (2)\", \"6\":\"COSTO PER 3 MESI FISSI DALLA DATA DI ADESIONE (2)\", \"7\":\"WORDING\"}' WHERE product_id = 4");
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
