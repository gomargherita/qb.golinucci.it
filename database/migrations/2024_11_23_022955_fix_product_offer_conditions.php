<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixProductOfferConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '37',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '37',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '38',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '38',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '39',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '39',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '40',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '40',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '41',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '41',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '42',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '42',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '43',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '43',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '44',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '44',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '45',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '45',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '46',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '46',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '47',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '47',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '48',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '48',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '49',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '49',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '50',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '50',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '51',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '51',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '52',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '52',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '53',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '53',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '54',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '54',
                'conditional_field_id' => '2',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '55',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '55',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '56',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '56',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '57',
                'conditional_field_id' => '1',
                'selected_field_value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '57',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '58',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '58',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '59',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '59',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '60',
                'conditional_field_id' => '1',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '60',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '61',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '61',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '62',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '62',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '63',
                'conditional_field_id' => '1',
                'selected_field_value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '63',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '64',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '64',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '65',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '65',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '66',
                'conditional_field_id' => '1',
                'selected_field_value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '66',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '67',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '67',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '68',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '68',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '69',
                'conditional_field_id' => '1',
                'selected_field_value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '69',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '70',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '70',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '71',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '71',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        
        
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '72',
                'conditional_field_id' => '1',
                'selected_field_value' => '6',
                'created_at' => now(),
                'updated_at' => now(),
                    )
                );
        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => '72',
                'conditional_field_id' => '2',
                'selected_field_value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
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
