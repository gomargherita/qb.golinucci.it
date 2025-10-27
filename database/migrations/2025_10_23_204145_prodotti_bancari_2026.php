<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProdottiBancari2026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 65, admin_fee = 25, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 73");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 40, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 74");

        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 28, admin_fee = 9, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 75");

        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 105, admin_fee = 30, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 76");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 63, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 77");
       
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 43, admin_fee = 9, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 78");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 50, admin_fee = 15, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 79");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933', total_payable = 31, admin_fee = 8, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 80");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 135, admin_fee = 40, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 81");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 74, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 82");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 105, admin_fee = 50, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 83");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 53, admin_fee = 19, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 84");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 175, admin_fee = 50, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 85");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 95, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 86");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 125, admin_fee = 45, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 87");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 71, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 88");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 155, admin_fee = 50, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 89");
       
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 91, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 90");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 380, admin_fee = 45, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 91");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, CHUBB - 0853ITFINC08653', total_payable = 196, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 92");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 125, admin_fee = 50, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 93");
        
        DB::statement("UPDATE product_offers SET sku = 'Italiana Assicurazioni - 076340933, Sara - 003232539JX', total_payable = 73, admin_fee = 14, gross_premium = 0, tax = 0, net_plus_access_premium = 0 WHERE id = 94");



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
