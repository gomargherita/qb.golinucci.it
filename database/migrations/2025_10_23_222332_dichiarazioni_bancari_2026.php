<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class DichiarazioniBancari2026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('product_declarations')->insert(
            array(
                'id' => '80',
                'product_id' => '2',
                'declaration' => 'dichiaro di aver presa visione dell’allegato 3 MUP (Modello Unico Precontrattuale), al link indicato in fondo alla pagina',
                'sort' => '25',
                'created_at' => now(),
                'updated_at' => now()
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
        //
    }
}
