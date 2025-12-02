<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CorrezioneRI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 109");
        DB::statement("UPDATE product_offers SET total_payable = '200.00' WHERE id = 109");
        
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 110");
        DB::statement("UPDATE product_offers SET total_payable = '103.00' WHERE id = 110");
        
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 111");
        DB::statement("UPDATE product_offers SET total_payable = '270.00' WHERE id = 111");
        
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 112");
        DB::statement("UPDATE product_offers SET total_payable = '140.00' WHERE id = 112");
        
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 113");
        DB::statement("UPDATE product_offers SET total_payable = '415.00' WHERE id = 113");
        
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 114");
        DB::statement("UPDATE product_offers SET total_payable = '211.00' WHERE id = 114");
        
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 115");
        DB::statement("UPDATE product_offers SET total_payable = '140.00' WHERE id = 115");
        
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 116");
        DB::statement("UPDATE product_offers SET total_payable = '70.00' WHERE id = 116");
        
        DB::statement("UPDATE product_offers SET admin_fee = '9.00' WHERE id = 117");
        DB::statement("UPDATE product_offers SET total_payable = '48.00' WHERE id = 117");
        
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 118");
        DB::statement("UPDATE product_offers SET total_payable = '200.00' WHERE id = 118");
    
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 119");
        DB::statement("UPDATE product_offers SET total_payable = '91.00' WHERE id = 119");
    
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 120");
        DB::statement("UPDATE product_offers SET total_payable = '215.00' WHERE id = 120");
    
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 121");
        DB::statement("UPDATE product_offers SET total_payable = '108.00' WHERE id = 121");
    
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 122");
        DB::statement("UPDATE product_offers SET total_payable = '155.00' WHERE id = 122");
    
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 123");
        DB::statement("UPDATE product_offers SET total_payable = '75.00' WHERE id = 123");
    
        DB::statement("UPDATE product_offers SET admin_fee = '50.00' WHERE id = 124");
        DB::statement("UPDATE product_offers SET total_payable = '165.00' WHERE id = 124");
    
        DB::statement("UPDATE product_offers SET admin_fee = '14.00' WHERE id = 125");
        DB::statement("UPDATE product_offers SET total_payable = '83.00' WHERE id = 125");
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
