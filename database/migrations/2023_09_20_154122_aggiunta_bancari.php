<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AggiuntaBancari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('products')->insert(
            array(
                'id' => 2,
                'account_id' => 1,
                'product_type_id' => 2,
                'name' => 'Convenzione Polizza Responsabilità Civile del Bancario',
                'description' => 'Emilia Romagna',
                'detailed_description' => 'Descrizione dettagliata Bancari ',
                'pre_declarations' => 'Pre-Dichiarazioni Bancari ',
                'post_declarations'=> 'Post-Dichiarazioni Bancari ',
                'conditions'=> 'Condizioni Bancari ',
                'client_details_declaration'=> 'Dichiarazione dettagli cliente Bancari ',
                'payments_description'=> 'Descrizione pagamenti Bancari ',
                'hex_color'=> '#32a852',
                'active' => 1
            )
        );

        DB::table('products')->insert(
            array(
                'id' => 3,
                'account_id' => 1,
                'product_type_id' => 2,
                'name' => 'Convenzione Polizza Responsabilità Civile del Bancario2',
                'description' => 'Sardegna',
                'detailed_description' => 'Descrizione dettagliata Bancari ',
                'pre_declarations' => 'Pre-Dichiarazioni Bancari ',
                'post_declarations'=> 'Post-Dichiarazioni Bancari ',
                'conditions'=> 'Condizioni Bancari ',
                'client_details_declaration'=> 'Dichiarazione dettagli cliente Bancari ',
                'payments_description'=> 'Descrizione pagamenti Bancari ',
                'hex_color'=> '#32a852',
                'active' => 1
            )
        );

        DB::table('products')->insert(
            array(
                'id' => 4,
                'account_id' => 1,
                'product_type_id' => 2,
                'name' => 'Convenzione Polizza Responsabilità Civile del Bancario3',
                'description' => 'RI',
                'detailed_description' => 'Descrizione dettagliata Bancari ',
                'pre_declarations' => 'Pre-Dichiarazioni Bancari ',
                'post_declarations'=> 'Post-Dichiarazioni Bancari ',
                'conditions'=> 'Condizioni Bancari ',
                'client_details_declaration'=> 'Dichiarazione dettagli cliente Bancari ',
                'payments_description'=> 'Descrizione pagamenti Bancari ',
                'hex_color'=> '#32a852',
                'active' => 1
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
