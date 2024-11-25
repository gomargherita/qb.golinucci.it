<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductOffersFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET old = 165 WHERE id = 37");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 165");
        DB::statement("UPDATE product_offers SET old = 166 WHERE id = 38");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 166");
        DB::statement("UPDATE product_offers SET old = 167 WHERE id = 39");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 167");
        DB::statement("UPDATE product_offers SET old = 168 WHERE id = 40");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 168");
        DB::statement("UPDATE product_offers SET old = 169 WHERE id = 41");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 169");
        DB::statement("UPDATE product_offers SET old = 170 WHERE id = 42");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 170");
        DB::statement("UPDATE product_offers SET old = 171 WHERE id = 43");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 171");
        DB::statement("UPDATE product_offers SET old = 172 WHERE id = 44");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 172");
        DB::statement("UPDATE product_offers SET old = 173 WHERE id = 45");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 173");
        DB::statement("UPDATE product_offers SET old = 174 WHERE id = 46");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 174");
        DB::statement("UPDATE product_offers SET old = 175 WHERE id = 47");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 175");
        DB::statement("UPDATE product_offers SET old = 176 WHERE id = 48");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 176");
        DB::statement("UPDATE product_offers SET old = 177 WHERE id = 49");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 177");
        DB::statement("UPDATE product_offers SET old = 178 WHERE id = 50");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 178");
        DB::statement("UPDATE product_offers SET old = 179 WHERE id = 51");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 179");
        DB::statement("UPDATE product_offers SET old = 180 WHERE id = 52");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 180");
        DB::statement("UPDATE product_offers SET old = 181 WHERE id = 53");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 181");
        DB::statement("UPDATE product_offers SET old = 182 WHERE id = 54");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 182");
        DB::statement("UPDATE product_offers SET old = 183 WHERE id = 55");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 183");
        DB::statement("UPDATE product_offers SET old = 184 WHERE id = 56");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 184");
        DB::statement("UPDATE product_offers SET old = 185 WHERE id = 57");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 185");
        DB::statement("UPDATE product_offers SET old = 186 WHERE id = 58");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 186");
        DB::statement("UPDATE product_offers SET old = 187 WHERE id = 59");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 187");
        DB::statement("UPDATE product_offers SET old = 188 WHERE id = 60");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 188");
        DB::statement("UPDATE product_offers SET old = 189 WHERE id = 61");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 189");
        DB::statement("UPDATE product_offers SET old = 190 WHERE id = 62");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 190");
        DB::statement("UPDATE product_offers SET old = 191 WHERE id = 63");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 191");
        DB::statement("UPDATE product_offers SET old = 192 WHERE id = 64");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 192");
        DB::statement("UPDATE product_offers SET old = 193 WHERE id = 65");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 193");
        DB::statement("UPDATE product_offers SET old = 194 WHERE id = 66");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 194");
        DB::statement("UPDATE product_offers SET old = 195 WHERE id = 67");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 195");
        DB::statement("UPDATE product_offers SET old = 196 WHERE id = 68");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 196");
        DB::statement("UPDATE product_offers SET old = 197 WHERE id = 69");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 197");
        DB::statement("UPDATE product_offers SET old = 198 WHERE id = 70");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 198");
        DB::statement("UPDATE product_offers SET old = 199 WHERE id = 71");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 199");
        DB::statement("UPDATE product_offers SET old = 200 WHERE id = 72");
        DB::statement("UPDATE product_offers SET old = null WHERE id = 200");
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
