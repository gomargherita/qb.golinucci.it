<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CountersSa extends Migration
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
                'id' => '10',
                'product_id' => '3',
                'payment_gateway_id' => '1',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BKS-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '11',
                'product_id' => '3',
                'payment_gateway_id' => '2',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BMS-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '12',
                'product_id' => '3',
                'payment_gateway_id' => '3',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23CAS-'
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
