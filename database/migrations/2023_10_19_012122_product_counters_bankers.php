<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductCountersBankers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('product_counters')->insert(
            array(
                'id' => '4',
                'product_id' => '2',
                'payment_gateway_id' => '1',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BK-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '5',
                'product_id' => '2',
                'payment_gateway_id' => '2',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BM-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '6',
                'product_id' => '2',
                'payment_gateway_id' => '3',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23CA-'
                //'created_at' => now(),
                //'updated_at' => now()
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
