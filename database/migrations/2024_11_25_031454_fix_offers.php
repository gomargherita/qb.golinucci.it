<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 37");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 38");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 39");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 40");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 41");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 42");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 43");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 44");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 45");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 46");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 47");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 48");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 49");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 50");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 51");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 52");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 53");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 54");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 55");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 56");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 57");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 58");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 59");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 60");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 61");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 62");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 63");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 64");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 65");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 66");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 67");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 68");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 69");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 70");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 71");
        DB::statement("UPDATE product_offers SET hide = 0 WHERE id = 72");
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
