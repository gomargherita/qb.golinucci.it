<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CountersRI extends Migration
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
                'id' => '7',
                'product_id' => '4',
                'payment_gateway_id' => '1',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BKN-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '8',
                'product_id' => '4',
                'payment_gateway_id' => '2',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23BMN-'
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_counters')->insert(
            array(
                'id' => '9',
                'product_id' => '4',
                'payment_gateway_id' => '3',
                'year' => '2023',
                'counter' => '1',
                'prefix' => '23CAN-'
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
