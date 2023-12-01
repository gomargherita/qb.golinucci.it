<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MediciProductConditionsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('conditional_fields')->insert(
            array(
                'id' => 5,
                'field_name' => 'telefono_ufficio',
                'field_label' => 'Telefono ufficio',
                'field_type' => 'text',
                'field_value' => '""',
                'only_client_details' => 1
            ));

        DB::table('conditional_fields')->insert(
        array(
            'id' => 6,
            'field_name' => 'appartenenza',
            'field_label' => 'First/Cisl Territoriale di appartenenza',
            'field_type' => 'text',
            'field_value' => '""',
            'only_client_details' => 1
        ));

        DB::table('conditional_fields')->insert(
        array(
            'id' => 7,
            'field_name' => 'banca',
            'field_label' => 'Banca',
            'field_type' => 'text',
            'field_value' => '""',
            'only_client_details' => 1
        ));

        DB::table('conditional_fields')->insert(
        array(
            'id' => 8,
            'field_name' => 'abi',
            'field_label' => 'Codice ABI',
            'field_type' => 'text',
            'field_value' => '""',
            'only_client_details' => 1
        ));

        DB::table('conditional_fields')->insert(
            array(
                'id' => 9,
                'field_name' => 'regione_di_appartenenza',
                'field_label' => 'Regione di appartenenza',
                'field_type' => 'select',
                'field_value' => '"{\"1\":\"Abruzzo\",\"2\":\"Basilicata\",\"3\":\"Calabria\",\"4\":\"Campania\",\"5\":\"Friuli Venezia Giulia\",\"6\":\"Lazio\",\"7\":\"Liguria\",\"8\":\"Lombardia\",\"9\":\"Marche\",\"10\":\"Molise\",\"11\":\"Piemonte\",\"12\":\"Puglia\",\"13\":\"Sardegna\",\"14\":\"Sicilia\",\"15\":\"Toscana\",\"16\":\"Trentino Alto Adige\",\"17\":\"Umbria\",\"18\":\"Val d Aosta\",\"19\":\"Veneto\"}"',
                'only_client_details' => 1
            ));

        
        /* ------------------------- ER ------------------------- */
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 2,
                'conditional_field_id' => 5
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 2,
                'conditional_field_id' => 6
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 2,
                'conditional_field_id' => 7
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 2,
                'conditional_field_id' => 8
            ));

        /* ------------------------- SA ------------------------- */
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 3,
                'conditional_field_id' => 5
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 3,
                'conditional_field_id' => 7
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 3,
                'conditional_field_id' => 8
            ));

        /* ------------------------- RI ------------------------- */
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 4,
                'conditional_field_id' => 5
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 4,
                'conditional_field_id' => 7
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 4,
                'conditional_field_id' => 8
            ));
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 4,
                'conditional_field_id' => 9
            ));




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
