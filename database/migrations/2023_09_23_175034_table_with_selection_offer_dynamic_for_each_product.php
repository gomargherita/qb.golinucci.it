<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Product;



class TableWithSelectionOfferDynamicForEachProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_offer_tables', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->constrained();
            $table->longText('table_elements');
        });

        DB::table('product_offer_tables')->insert(
            array(
                'product_id' => 1,
                'table_elements' => '{"1":"MASSIMALE PER DANNI MATERIALI E IMMATERIALI PER LA PROFESSIONE SANITARIA","2":"MASSIMALE R.C PATRIMONIALE DA FUNZIONI DI CARATTERE AMMINSTRATIVO E/O ORGANIZZATIVO","3":"FRANCHIGIA","4":"COSTO ANNUO", "5":"WORDING"}'
            )
        );

        DB::table('product_offer_tables')->insert(
            array(
                'product_id' => 2,
                'table_elements' => '{"1":"AMMANCHI CASSA - MASSIMALE PER SINISTRO E PER ANNO (1)","2":"R.C. PATRIMONIALE MASSIMALE PER SINISTRO E PER ANNO (2) (3)","3":"SPESE LEGALI MASSIMALE PER SINISTRO E PER ANNO (4)  ","4":"COSTO PER COPERTURA FINO AL 31-12-2024", "5":"COSTO PER 7 MESI FISSI DALLA DATA DI ADESIONE (5)", "6":"COSTO PER 3 MESI FISSI DALLA DATA DI ADESIONE (5)", "7":"WORDING"}'
            )
        );

        DB::table('product_offer_tables')->insert(
            array(
                'product_id' => 3,
                'table_elements' => '{"1":"AMMANCHI CASSA - MASSIMALE PER SINISTRO E PER ANNO (1)","2":"R.C. PATRIMONIALE MASSIMALE PER SINISTRO E PER ANNO (2) (3)","3":"SPESE LEGALI MASSIMALE PER SINISTRO E PER ANNO (4)  ","4":"COSTO PER COPERTURA FINO AL 31-12-2024", "5":"COSTO PER 7 MESI FISSI DALLA DATA DI ADESIONE (5)", "6":"COSTO PER 3 MESI FISSI DALLA DATA DI ADESIONE (5)", "7":"WORDING"}'
            )
        );

        DB::table('product_offer_tables')->insert(
            array(
                'product_id' => 4,
                'table_elements' => '{"1":"AMMANCHI CASSA - MASSIMALE PER SINISTRO E PER ANNO (1)","2":"R.C. PATRIMONIALE MASSIMALE PER SINISTRO E PER ANNO (2) (3)","3":"SPESE LEGALI MASSIMALE PER SINISTRO E PER ANNO (4)  ","4":"COSTO PER COPERTURA FINO AL 31-12-2024", "5":"COSTO PER 7 MESI FISSI DALLA DATA DI ADESIONE (5)", "6":"COSTO PER 3 MESI FISSI DALLA DATA DI ADESIONE (5)", "7":"WORDING"}'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_offer_tables');
    }
}
