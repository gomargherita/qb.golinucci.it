<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class FixSARA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 87");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 88");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 89");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 90");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 93");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 32635LG' WHERE id = 94");
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
