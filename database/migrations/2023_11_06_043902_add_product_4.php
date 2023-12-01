<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProduct4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contracts')->insert(
            array(
                'id' => '6',
                'contract_filepath' => '/storage/contracts/adesione_friuli_north.pdf',
                'dip' => '/storage/contracts/adesione_friuli_north.pdf',
                
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '95',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 1 12 Months',
                'sku' => '',
                'total_payable' => '80.0',
                'admin_fee' => '25.0',
                'gross_premium' => '55.0',
                'tax' => '10.01',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '44.99',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '96',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 1 7 Months',
                'sku' => '',
                'total_payable' => '45.0',
                'admin_fee' => '9.0',
                'gross_premium' => '36.0',
                'tax' => '6.55',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '29.45',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '7',
                'combination' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '97',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 1 3 Months',
                'sku' => '',
                'total_payable' => '32.0',
                'admin_fee' => '6.0',
                'gross_premium' => '26.0',
                'tax' => '4.73',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '21.27',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '3',
                'combination' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '98',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 2 12 Months',
                'sku' => '',
                'total_payable' => '105.0',
                'admin_fee' => '25.0',
                'gross_premium' => '80.0',
                'tax' => '14.56',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '65.44',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '2',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '99',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 2 7 Months',
                'sku' => '',
                'total_payable' => '55.0',
                'admin_fee' => '9.0',
                'gross_premium' => '46.0',
                'tax' => '8.37',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '37.63',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '7',
                'combination' => '2',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '100',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 2 3 Months',
                'sku' => '',
                'total_payable' => '35.0',
                'admin_fee' => '6.0',
                'gross_premium' => '29.0',
                'tax' => '5.28',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '23.72',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '3',
                'combination' => '2',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '101',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 3 12 Months',
                'sku' => '',
                'total_payable' => '85.0',
                'admin_fee' => '25.0',
                'gross_premium' => '60.0',
                'tax' => '10.92',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '49.08',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '12',
                'combination' => '3',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '102',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 3 7 Months',
                'sku' => '',
                'total_payable' => '50.0',
                'admin_fee' => '9.0',
                'gross_premium' => '41.0',
                'tax' => '7.46',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '33.54',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'months' => '7',
                'combination' => '3',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '103',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 4 12 Months',
                'sku' => '',
                'total_payable' => '50.0',
                'admin_fee' => '25.0',
                'gross_premium' => '25.0',
                'tax' => '4.55',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '20.45',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '12',
                'combination' => '4',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '104',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 4 7 Months',
                'sku' => '',
                'total_payable' => '30.0',
                'admin_fee' => '9.0',
                'gross_premium' => '21.0',
                'tax' => '3.82',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '17.18',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '7',
                'combination' => '4',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '105',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 5 12 Months',
                'sku' => '',
                'total_payable' => '115.0',
                'admin_fee' => '25.0',
                'gross_premium' => '90.0',
                'tax' => '16.38',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '73.62',
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
                'id' => '106',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 5 7 Months',
                'sku' => '',
                'total_payable' => '65.0',
                'admin_fee' => '9.0',
                'gross_premium' => '56.0',
                'tax' => '10.19',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '45.81',
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
                'id' => '107',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 6 12 Months',
                'sku' => '',
                'total_payable' => '140.0',
                'admin_fee' => '25.0',
                'gross_premium' => '115.0',
                'tax' => '20.93',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '94.07',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '250000',
                'legal_fees' => '62500',
                'active' => '1',
                'months' => '12',
                'combination' => '6',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '108',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 6 7 Months',
                'sku' => '',
                'total_payable' => '78.0',
                'admin_fee' => '9.0',
                'gross_premium' => '69.0',
                'tax' => '12.56',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '56.44',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '250000',
                'legal_fees' => '62500',
                'active' => '1',
                'months' => '7',
                'combination' => '6',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '109',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 7 12 Months',
                'sku' => '',
                'total_payable' => '175.0',
                'admin_fee' => '25.0',
                'gross_premium' => '150.0',
                'tax' => '27.30',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '122.70',
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
                'id' => '110',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 7 7 Months',
                'sku' => '',
                'total_payable' => '98.0',
                'admin_fee' => '9.0',
                'gross_premium' => '89.0',
                'tax' => '16.20',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '72.80',
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
                'id' => '111',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 8 12 Months',
                'sku' => '',
                'total_payable' => '245.0',
                'admin_fee' => '25.0',
                'gross_premium' => '220.0',
                'tax' => '40.04',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '179.96',
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
                'id' => '112',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 8 7 Months',
                'sku' => '',
                'total_payable' => '135.0',
                'admin_fee' => '9.0',
                'gross_premium' => '126.0',
                'tax' => '22.93',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '103.07',
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
                'id' => '113',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 9 12 Months',
                'sku' => '',
                'total_payable' => '390.0',
                'admin_fee' => '25.0',
                'gross_premium' => '365.0',
                'tax' => '66.43',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '298.57',
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
                'id' => '114',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 9 7 Months',
                'sku' => '',
                'total_payable' => '206.0',
                'admin_fee' => '9.0',
                'gross_premium' => '197.0',
                'tax' => '35.85',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '161.15',
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
                'id' => '115',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 10 12 Months',
                'sku' => '',
                'total_payable' => '115.0',
                'admin_fee' => '25.0',
                'gross_premium' => '90.0',
                'tax' => '16.38',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '73.62',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '12',
                'combination' => '10',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '116',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 10 7 Months',
                'sku' => '',
                'total_payable' => '65.0',
                'admin_fee' => '9.0',
                'gross_premium' => '56.0',
                'tax' => '10.19',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '45.81',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '7',
                'combination' => '10',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '117',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 10 3 Months',
                'sku' => '',
                'total_payable' => '45.0',
                'admin_fee' => '6.0',
                'gross_premium' => '39.0',
                'tax' => '7.10',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '31.90',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'months' => '3',
                'combination' => '10',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '118',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 11 12 Months',
                'sku' => '',
                'total_payable' => '175.0',
                'admin_fee' => '25.0',
                'gross_premium' => '150.0',
                'tax' => '27.30',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '122.70',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '12',
                'combination' => '11',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '119',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 11 7 Months',
                'sku' => '',
                'total_payable' => '86.0',
                'admin_fee' => '9.0',
                'gross_premium' => '77.0',
                'tax' => '14.01',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '62.99',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '7',
                'combination' => '11',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '120',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 12 12 Months',
                'sku' => '',
                'total_payable' => '190.0',
                'admin_fee' => '25.0',
                'gross_premium' => '165.0',
                'tax' => '30.03',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '134.97',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '12',
                'combination' => '12',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '121',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 12 7 Months',
                'sku' => '',
                'total_payable' => '103.0',
                'admin_fee' => '9.0',
                'gross_premium' => '94.0',
                'tax' => '17.11',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '76.89',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'months' => '7',
                'combination' => '12',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '122',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 13 12 Months',
                'sku' => '',
                'total_payable' => '130.0',
                'admin_fee' => '25.0',
                'gross_premium' => '105.0',
                'tax' => '19.11',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '85.89',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '12',
                'combination' => '13',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '123',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 13 7 Months',
                'sku' => '',
                'total_payable' => '70.0',
                'admin_fee' => '9.0',
                'gross_premium' => '61.0',
                'tax' => '11.10',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '49.90',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '7',
                'combination' => '13',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '124',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 14 12 Months',
                'sku' => '',
                'total_payable' => '170.0',
                'admin_fee' => '25.0',
                'gross_premium' => '145.0',
                'tax' => '26.39',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '118.61',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '12',
                'combination' => '14',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '125',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 14 7 Months',
                'sku' => '',
                'total_payable' => '78.0',
                'admin_fee' => '9.0',
                'gross_premium' => '69.0',
                'tax' => '12.56',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '56.44',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '7',
                'combination' => '14',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '126',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 15 12 Months',
                'sku' => '',
                'total_payable' => '70.0',
                'admin_fee' => '25.0',
                'gross_premium' => '45.0',
                'tax' => '8.19',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '36.81',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '12',
                'combination' => '15',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '127',
                'product_id' => '4',
                'contract_id' => '6',
                'name' => 'North Bank Employees Com 15 7 Months',
                'sku' => '',
                'total_payable' => '38.0',
                'admin_fee' => '9.0',
                'gross_premium' => '29.0',
                'tax' => '5.28',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '23.72',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'months' => '7',
                'combination' => '15',
                'hide' => '1',
            )
        );

        //Dichiarazioni

        DB::table('product_declarations')->insert(
            array(
                'id' => '28',
                'product_id' => '4',
                'declaration' => 'di essere domiciliato in Italia',
                'sort' => '10',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '29',
                'product_id' => '4',
                'declaration' => 'di essere a conoscenza del "periodo di ripensamento" di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue - D.Lgs 58/2014), segnalando il mio diritto di recesso tramite email a bancari@golinucci.it o info@pec.golinucci.it',
                'sort' => '20',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '30',
                'product_id' => '4',
                'declaration' => 'di aver preso visione dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EaMkTb6ZqwxGus30WiCW9JEB7BOv73nsG-RmQuRsQcbDvg?e=1Us7f4">allegato 3</a> sugli obblighi di comportamento cui gli intermediari sono tenuti nei confronti del Contraente ed Assicurati e dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/ET4BR7TMiqNAhfFIL8W5ihABx25sf7pvEx9mCLE6U2bKIQ?e=SJ90Ga">allegato 4</a> riguardante gli elementi informativi su Golinucci srl',
                'sort' => '30',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '31',
                'product_id' => '4',
                'declaration' => 'di aver autorizzato con <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EV3YYoZrRNhOqeEhCQptfeMBEI-d2CZB5AjqNpTeEwHphg?e=qbmhhO">lettera incarico</a> il broker di Assicurazione GOLINUCCI srl alla gestione dei contratti assicurativi che ho selezionato online',
                'sort' => '40',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '32',
                'product_id' => '4',
                'declaration' => 'di aver presa visione del Set Informativo relativo ai contratti selezionati online',
                'sort' => '50',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '33',
                'product_id' => '4',
                'declaration' => 'che la Golinucci srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte',
                'sort' => '60',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '34',
                'product_id' => '4',
                'declaration' => 'di autorizzare – ai sensi art. 61 del Regolamento IVASS n.40 del 02/08/2018 – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole non cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato nella pagina “Informazioni su Assicurato”',
                'sort' => '70',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '35',
                'product_id' => '4',
                'declaration' => 'di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi.',
                'sort' => '80',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '36',
                'product_id' => '4',
                'declaration' => 'di autorizzare la Golinucci srl al trattamento dei dati personali e dichiaro di aver preso visione <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EVU8uj5KvMNGhlvuYS2pfsoBfr_-Q8f3F2TOnjtrXYF5VQ?e=JeXpp1">dell’Informativa Privacy</a> fornitami ai sensi dell’art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR',
                'sort' => '90',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '37',
                'product_id' => '4',
                'declaration' => 'di approvare l’informativa privacy del sito web',
                'sort' => '100',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '38',
                'product_id' => '4',
                'declaration' => 'di aver letto l’informativa contrattuale messa a disposizione degli assicurati dal contraente (per polizze con data di distribuzione antecedente il 01/12/2010), nel sito www.golinucci.it/',
                'sort' => '110',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '39',
                'product_id' => '4',
                'declaration' => 'di confermare che la polizza Ammanchi di Cassa - R.C. Patrimoniale con i massimale da me scelti è coerente alle mie esigenze',
                'sort' => '120',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '40',
                'product_id' => '4',
                'declaration' => 'di confermare che le dichiarazioni rese sono conformi a verità e di non avere omesso alcun elemento influente sulla valutazione del rischio',
                'sort' => '130',
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
