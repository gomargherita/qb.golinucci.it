<?php

use App\Models\Contract;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateBankerProductsEr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_offers', function (Blueprint $table) {
            $table->integer('legal_fees');
            $table->integer('combination');
            $table->integer('hide');
        });

        DB::statement("ALTER TABLE product_offers MODIFY COLUMN created_at TIMESTAMP AFTER hide");
        DB::statement("ALTER TABLE product_offers MODIFY COLUMN updated_at TIMESTAMP AFTER created_at");
        DB::statement("ALTER TABLE product_offers MODIFY COLUMN deleted_at TIMESTAMP AFTER updated_at");

        
        DB::table('product_offers')->insert(
            array(
                'id' => '73',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 1 12 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '65.0',
                'admin_fee' => '25.0',
                'gross_premium' => '40.0',
                'tax' => '7.28',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '32.72',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '12',
                'combination' => '1',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '74',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 1 7 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '40.0',
                'admin_fee' => '14.0',
                'gross_premium' => '26.0',
                'tax' => '4.73',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '21.27',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '7',
                'combination' => '1',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '75',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 1 3 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '28.0',
                'admin_fee' => '9.0',
                'gross_premium' => '19.0',
                'tax' => '3.46',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '15.54',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '3',
                'combination' => '1',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '76',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 2 12 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '105.0',
                'admin_fee' => '40.0',
                'gross_premium' => '65.0',
                'tax' => '11.83',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '53.17',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '12',
                'combination' => '2',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '77',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 2 7 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '63.0',
                'admin_fee' => '14.0',
                'gross_premium' => '49.0',
                'tax' => '8.92',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '40.08',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '7',
                'combination' => '2',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '78',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 2 3 Months',
                'sku' => '002313579 002313580',
                'total_payable' => '43.0',
                'admin_fee' => '9.0',
                'gross_premium' => '34.0',
                'tax' => '6.19',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '27.81',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '3',
                'combination' => '2',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '79',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 3 12 Months',
                'sku' => '002313580',
                'total_payable' => '50.0',
                'admin_fee' => '30.0',
                'gross_premium' => '20.0',
                'tax' => '3.64',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '16.36',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '12',
                'combination' => '3',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '80',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 3 7 Months',
                'sku' => '002313580',
                'total_payable' => '29.0',
                'admin_fee' => '14.0',
                'gross_premium' => '15.0',
                'tax' => '2.73',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '12.27',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '7',
                'combination' => '3',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '81',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 4 12 Months',
                'sku' => '002313579 002313580 0853ITFINC08653',
                'total_payable' => '135.0',
                'admin_fee' => '50.0',
                'gross_premium' => '85.0',
                'tax' => '15.47',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '69.53',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '12',
                'combination' => '4',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '82',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 4 7 Months',
                'sku' => '002313579 002313580 0853ITFINC08653',
                'total_payable' => '77.0',
                'admin_fee' => '14.0',
                'gross_premium' => '63.0',
                'tax' => '11.47',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '51.53',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '7',
                'combination' => '4',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '83',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 5 12 Months',
                'sku' => '002313580  0853ITFINC08653',
                'total_payable' => '110.0',
                'admin_fee' => '50.0',
                'gross_premium' => '60.0',
                'tax' => '10.92',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '49.08',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '12',
                'combination' => '5',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '84',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 5 7 Months',
                'sku' => '002313579 002313580 0853ITFINC08653',
                'total_payable' => '62.0',
                'admin_fee' => '14.0',
                'gross_premium' => '48.0',
                'tax' => '8.74',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '39.26',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '7',
                'combination' => '5',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '85',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 6 12 Months',
                'sku' => '002313579 002313580 0853ITFINC08653',
                'total_payable' => '190.0',
                'admin_fee' => '50.0',
                'gross_premium' => '140.0',
                'tax' => '25.48',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '114.52',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'months' => '12',
                'combination' => '6',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '86',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 6 7 Months',
                'sku' => '002313580  0853ITFINC08653',
                'total_payable' => '110.0',
                'admin_fee' => '14.0',
                'gross_premium' => '96.0',
                'tax' => '17.47',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '78.53',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'months' => '7',
                'combination' => '6',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '87',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 7 12 Months',
                'sku' => '002313580 003232539JX',
                'total_payable' => '125.0',
                'admin_fee' => '50.0',
                'gross_premium' => '75.0',
                'tax' => '13.65',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '61.35',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'months' => '12',
                'combination' => '7',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '88',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 7 7 Months',
                'sku' => '002313580 003232539JX',
                'total_payable' => '73.0',
                'admin_fee' => '14.0',
                'gross_premium' => '59.0',
                'tax' => '10.74',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '48.26',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'months' => '7',
                'combination' => '7',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '89',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 8 12 Months',
                'sku' => '002313580 003232539JX',
                'total_payable' => '170.0',
                'admin_fee' => '50.0',
                'gross_premium' => '120.0',
                'tax' => '21.84',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '91.16',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '1000000',
                'legal_fees' => '250000',
                'active' => '1',
                'months' => '12',
                'combination' => '8',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '90',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 8 7 Months',
                'sku' => '002313580 003232539JX',
                'total_payable' => '95.0',
                'admin_fee' => '14.0',
                'gross_premium' => '81.0',
                'tax' => '14.74',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '66.26',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '1000000',
                'legal_fees' => '250000',
                'active' => '1',
                'months' => '7',
                'combination' => '8',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '91',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 9 12 Months',
                'sku' => '002313580 0853ITFINC08653',
                'total_payable' => '380.0',
                'admin_fee' => '50.0',
                'gross_premium' => '330.0',
                'tax' => '60.06',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '269.94',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '2000000',
                'legal_fees' => '500000',
                'active' => '1',
                'months' => '12',
                'combination' => '9',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '92',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 9 7 Months',
                'sku' => '002313580 0853ITFINC08653',
                'total_payable' => '200.0',
                'admin_fee' => '14.0',
                'gross_premium' => '186.0',
                'tax' => '33.85',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '152.15',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '2000000',
                'legal_fees' => '500000',
                'active' => '1',
                'months' => '7',
                'combination' => '9',
                'hide' => '1',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '93',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 10 12 Months',
                'sku' => '002313579 002313580 003232539JX',
                'total_payable' => '125.0',
                'admin_fee' => '50.0',
                'gross_premium' => '75.0',
                'tax' => '13.65',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '61.35',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '12',
                'combination' => '10',
                'hide' => '0',
            )
        );
        DB::table('product_offers')->insert(
            array(
                'id' => '94',
                'product_id' => '2',
                'contract_id' => '5',
                'name' => 'ER Bank Employees Com 10 7 Months',
                'sku' => '002313579 002313580 003232539JX',
                'total_payable' => '73.0',
                'admin_fee' => '14.0',
                'gross_premium' => '59.0',
                'tax' => '10.74',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '48.26',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '7',
                'combination' => '10',
                'hide' => '1',
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
        /*Schema::table('prouct_offers', function (Blueprint $table) {
            $table->dropColumn('legal_fees');
            $table->dropColumn('combination');
        }); */
    }
}
