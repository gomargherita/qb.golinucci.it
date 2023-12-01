<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProduct3 extends Migration
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
                'id' => '7',
                'contract_filepath' => '/storage/contracts/modulo-adesione-sardegna-2023.pdf',
                'dip' => '/storage/contracts/modulo-adesione-sardegna-2023.pdf',
                
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '128',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 1 12 Months',
                'sku' => '',
                'total_payable' => '80.0',
                'admin_fee' => '25.0',
                'gross_premium' => '55.0',
                'tax' => '10.01',
                'combination' => '1',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'net_plus_access_premium' => '44.99',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '129',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 1 7 Months',
                'sku' => '',
                'total_payable' => '45.0',
                'admin_fee' => '9.0',
                'gross_premium' => '36.0',
                'tax' => '6.55',
                'net_plus_access_premium' => '29.45',
                'combination' => '1',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '130',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 1 3 Months',
                'sku' => '',
                'total_payable' => '32.0',
                'admin_fee' => '6.0',
                'gross_premium' => '26.0',
                'tax' => '4.73',
                'net_plus_access_premium' => '21.27',
                'combination' => '1',
                'months' => '3',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '131',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 2 12 Months',
                'sku' => '',
                'total_payable' => '105.0',
                'admin_fee' => '25.0',
                'gross_premium' => '80.0',
                'tax' => '14.56',
                'net_plus_access_premium' => '65.44',
                'combination' => '2',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '132',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 2 7 Months',
                'sku' => '',
                'total_payable' => '55.0',
                'admin_fee' => '9.0',
                'gross_premium' => '46.0',
                'tax' => '8.37',
                'net_plus_access_premium' => '37.63',
                'combination' => '2',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '133',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 2 3 Months',
                'sku' => '',
                'total_payable' => '35.0',
                'admin_fee' => '6.0',
                'gross_premium' => '29.0',
                'tax' => '5.28',
                'net_plus_access_premium' => '23.72',
                'combination' => '2',
                'months' => '2',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '0',
                'legal_fees' => '0',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '134',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 3 12 Months',
                'sku' => '',
                'total_payable' => '85.0',
                'admin_fee' => '25.0',
                'gross_premium' => '60.0',
                'tax' => '10.92',
                'net_plus_access_premium' => '49.08',
                'combination' => '3',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '135',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 3 7 Months',
                'sku' => '',
                'total_payable' => '50.0',
                'admin_fee' => '9.0',
                'gross_premium' => '41.0',
                'tax' => '7.46',
                'net_plus_access_premium' => '33.54',
                'combination' => '3',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '20000',
                'legal_fees' => '5000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '136',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 4 12 Months',
                'sku' => '',
                'total_payable' => '50.0',
                'admin_fee' => '25.0',
                'gross_premium' => '25.0',
                'tax' => '4.55',
                'net_plus_access_premium' => '20.45',
                'combination' => '4',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '137',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 4 7 Months',
                'sku' => '',
                'total_payable' => '30.0',
                'admin_fee' => '9.0',
                'gross_premium' => '21.0',
                'tax' => '3.82',
                'net_plus_access_premium' => '17.18',
                'combination' => '4',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '138',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 5 12 Months',
                'sku' => '',
                'total_payable' => '115.0',
                'admin_fee' => '25.0',
                'gross_premium' => '90.0',
                'tax' => '16.38',
                'net_plus_access_premium' => '73.62',
                'combination' => '5',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '139',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 5 7 Months',
                'sku' => '',
                'total_payable' => '65.0',
                'admin_fee' => '9.0',
                'gross_premium' => '56.0',
                'tax' => '10.19',
                'net_plus_access_premium' => '45.81',
                'combination' => '5',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '140',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 6 12 Months',
                'sku' => '',
                'total_payable' => '140.0',
                'admin_fee' => '25.0',
                'gross_premium' => '115.0',
                'tax' => '20.93',
                'net_plus_access_premium' => '94.07',
                'combination' => '6',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '250000',
                'legal_fees' => '62500',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '141',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 6 7 Months',
                'sku' => '',
                'total_payable' => '78.0',
                'admin_fee' => '9.0',
                'gross_premium' => '69.0',
                'tax' => '12.56',
                'net_plus_access_premium' => '56.44',
                'combination' => '6',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '250000',
                'legal_fees' => '62500',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '142',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 7 12 Months',
                'sku' => '',
                'total_payable' => '175.0',
                'admin_fee' => '25.0',
                'gross_premium' => '150.0',
                'tax' => '27.30',
                'net_plus_access_premium' => '122.70',
                'combination' => '7',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '143',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 7 7 Months',
                'sku' => '',
                'total_payable' => '98.0',
                'admin_fee' => '9.0',
                'gross_premium' => '89.0',
                'tax' => '16.20',
                'net_plus_access_premium' => '72.80',
                'combination' => '7',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '125000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '144',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 8 12 Months',
                'sku' => '',
                'total_payable' => '245.0',
                'admin_fee' => '25.0',
                'gross_premium' => '220.0',
                'tax' => '40.04',
                'net_plus_access_premium' => '179.96',
                'combination' => '8',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '1000000',
                'legal_fees' => '250000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '145',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 8 7 Months',
                'sku' => '',
                'total_payable' => '135.0',
                'admin_fee' => '9.0',
                'gross_premium' => '126.0',
                'tax' => '22.93',
                'net_plus_access_premium' => '103.07',
                'combination' => '8',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '1000000',
                'legal_fees' => '250000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '146',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 9 12 Months',
                'sku' => '',
                'total_payable' => '390.0',
                'admin_fee' => '25.0',
                'gross_premium' => '365.0',
                'tax' => '66.43',
                'net_plus_access_premium' => '298.57',
                'combination' => '9',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '2000000',
                'legal_fees' => '500000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '147',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 9 7 Months',
                'sku' => '',
                'total_payable' => '206.0',
                'admin_fee' => '9.0',
                'gross_premium' => '197.0',
                'tax' => '35.85',
                'net_plus_access_premium' => '161.15',
                'combination' => '9',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '2000000',
                'legal_fees' => '500000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '148',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 10 12 Months',
                'sku' => '',
                'total_payable' => '115.0',
                'admin_fee' => '25.0',
                'gross_premium' => '90.0',
                'tax' => '16.38',
                'net_plus_access_premium' => '73.62',
                'combination' => '10',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '149',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 10 7 Months',
                'sku' => '',
                'total_payable' => '65.0',
                'admin_fee' => '9.0',
                'gross_premium' => '56.0',
                'tax' => '10.19',
                'net_plus_access_premium' => '45.81',
                'combination' => '10',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '150',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 10 3 Months',
                'sku' => '',
                'total_payable' => '45.0',
                'admin_fee' => '6.0',
                'gross_premium' => '39.0',
                'tax' => '7.10',
                'net_plus_access_premium' => '31.90',
                'combination' => '10',
                'months' => '3',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '60000',
                'legal_fees' => '15000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '151',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 11 12 Months',
                'sku' => '',
                'total_payable' => '175.0',
                'admin_fee' => '25.0',
                'gross_premium' => '150.0',
                'tax' => '27.30',
                'net_plus_access_premium' => '122.70',
                'combination' => '11',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '152',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 11 7 Months',
                'sku' => '',
                'total_payable' => '86.0',
                'admin_fee' => '9.0',
                'gross_premium' => '77.0',
                'tax' => '14.01',
                'net_plus_access_premium' => '62.99',
                'combination' => '11',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '153',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 12 12 Months',
                'sku' => '',
                'total_payable' => '190.0',
                'admin_fee' => '25.0',
                'gross_premium' => '165.0',
                'tax' => '30.03',
                'net_plus_access_premium' => '134.97',
                'combination' => '12',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '154',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 12 7 Months',
                'sku' => '',
                'total_payable' => '103.0',
                'admin_fee' => '9.0',
                'gross_premium' => '94.0',
                'tax' => '17.11',
                'net_plus_access_premium' => '76.89',
                'combination' => '12',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '150000',
                'legal_fees' => '37500',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '155',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 13 12 Months',
                'sku' => '',
                'total_payable' => '130.0',
                'admin_fee' => '25.0',
                'gross_premium' => '105.0',
                'tax' => '19.11',
                'net_plus_access_premium' => '85.89',
                'combination' => '13',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '156',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 13 7 Months',
                'sku' => '',
                'total_payable' => '70.0',
                'admin_fee' => '9.0',
                'gross_premium' => '61.0',
                'tax' => '11.10',
                'net_plus_access_premium' => '49.90',
                'combination' => '13',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '6000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '157',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 14 12 Months',
                'sku' => '',
                'total_payable' => '140.0',
                'admin_fee' => '25.0',
                'gross_premium' => '115.0',
                'tax' => '20.93',
                'net_plus_access_premium' => '94.07',
                'combination' => '14',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '158',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 14 7 Months',
                'sku' => '',
                'total_payable' => '78.0',
                'admin_fee' => '9.0',
                'gross_premium' => '69.0',
                'tax' => '12.56',
                'net_plus_access_premium' => '56.44',
                'combination' => '14',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '10000',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'hide' => '1',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '159',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 15 12 Months',
                'sku' => '',
                'total_payable' => '70.0',
                'admin_fee' => '25.0',
                'gross_premium' => '45.0',
                'tax' => '8.19',
                'net_plus_access_premium' => '36.81',
                'combination' => '15',
                'months' => '12',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '160',
                'product_id' => '3',
                'contract_id' => '7',
                'name' => 'sardinia Bank Employees Com 15 7 Months',
                'sku' => '',
                'total_payable' => '38.0',
                'admin_fee' => '9.0',
                'gross_premium' => '29.0',
                'tax' => '5.28',
                'net_plus_access_premium' => '23.72',
                'combination' => '15',
                'months' => '7',
                'net_premium' => '0',
                'accessory' => '0',
                'description' => 'vuoto',
                'indemnity_limit' => '0',
                'indemnity_limit_patrimonial' => '100000',
                'legal_fees' => '25000',
                'active' => '1',
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
        //
    }
}
