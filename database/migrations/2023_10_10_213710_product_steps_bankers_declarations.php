<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductStepsBankersDeclarations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Steps
        DB::table('product_steps')->insert(
            array(
                'id' => '8',
                'product_id' => '2',
                'step_number' => '1',
                'step_name' => 'Anagrafica',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '9',
                'product_id' => '2',
                'step_number' => '2',
                'step_name' => 'Scelta offerta prodotto',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '10',
                'product_id' => '2',
                'step_number' => '3',
                'step_name' => 'Dichiarazioni',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '11',
                'product_id' => '2',
                'step_number' => '4',
                'step_name' => 'Pagamento',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '12',
                'product_id' => '2',
                'step_number' => '5',
                'step_name' => 'Completato',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );


        //Steps
        DB::table('product_steps')->insert(
            array(
                'id' => '13',
                'product_id' => '3',
                'step_number' => '1',
                'step_name' => 'Anagrafica',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '14',
                'product_id' => '3',
                'step_number' => '2',
                'step_name' => 'Scelta offerta prodotto',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '15',
                'product_id' => '3',
                'step_number' => '3',
                'step_name' => 'Dichiarazioni',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '16',
                'product_id' => '3',
                'step_number' => '4',
                'step_name' => 'Pagamento',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '17',
                'product_id' => '3',
                'step_number' => '5',
                'step_name' => 'Completato',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        //Steps
        DB::table('product_steps')->insert(
            array(
                'id' => '18',
                'product_id' => '4',
                'step_number' => '1',
                'step_name' => 'Anagrafica',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '19',
                'product_id' => '4',
                'step_number' => '2',
                'step_name' => 'Scelta offerta prodotto',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '20',
                'product_id' => '4',
                'step_number' => '3',
                'step_name' => 'Dichiarazioni',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '21',
                'product_id' => '4',
                'step_number' => '4',
                'step_name' => 'Pagamento',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '22',
                'product_id' => '4',
                'step_number' => '5',
                'step_name' => 'Completato',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        //Declarations

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
