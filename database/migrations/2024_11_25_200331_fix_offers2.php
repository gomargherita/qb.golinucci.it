<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixOffers2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 165");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 166");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 167");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 168");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 169");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 170");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 171");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 172");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 173");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 174");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 175");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 176");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 177");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 178");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 179");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 180");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 181");
        DB::statement("UPDATE product_offers SET contract_id = 11 WHERE id = 182");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 183");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 184");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 185");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 186");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 187");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 188");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 189");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 190");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 191");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 192");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 193");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 194");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 195");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 196");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 197");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 198");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 199");
        DB::statement("UPDATE product_offers SET contract_id = 12 WHERE id = 200");
        DB::statement("UPDATE contracts SET contract_filepath = '/storage/contracts/AGGIORNATO_WORDING_GIOVANE_MEDICO_2024.pdf' WHERE id = 8")
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
