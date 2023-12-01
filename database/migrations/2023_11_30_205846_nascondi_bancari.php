<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NascondiBancari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accounts')->insert(
            array(
                'id' => 2,
                'name' => 'Nascosto',                
            )
        );

        DB::statement("UPDATE products SET account_id = '2' WHERE id = 2");
        DB::statement("UPDATE products SET account_id = '2' WHERE id = 3");
        DB::statement("UPDATE products SET account_id = '2' WHERE id = 4");
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
