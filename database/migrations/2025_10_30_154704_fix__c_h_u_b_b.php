<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixCHUBB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 81");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 82");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 83");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 84");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 85");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 86");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 91");
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - ITFINC08653' WHERE id = 92");
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
