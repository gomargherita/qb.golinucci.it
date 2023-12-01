<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AddContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('dip');
        });

        DB::table('contracts')->insert(
            array(
                'id' => '5',
                'contract_filepath' => '/storage/contracts/estratto-condizioni-2023.pdf',
                'dip' => '/storage/contracts/estratto-condizioni-2023.pdf',
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
