<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProdottiMedici2024 extends Migration
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
                'id' => '9',
                'contract_filepath' => '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_1.000.000.pdf',
                'dip' => '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION A - 20232311AVN2634-LB.docx.pdf',
                'dip_aggiuntivo' => '/storage/contracts/DIP414_dip aggiuntivo section a2311AVN2553-LB.docx.pdf',
            )
        );

        DB::table('contracts')->insert(
            array(
                'id' => '10',
                'contract_filepath' => '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_1.000.000.pdf',
                'dip' => '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION B - 20232311AVN2648-LB.docx.pdf',
                'dip_aggiuntivo' => '/storage/contracts/DIP414_dip aggiuntivo section b-20232311AVN2608-LB.docx.pdf',
            )
        );

        DB::table('contracts')->insert(
            array(
                'id' => '11',
                'contract_filepath' => '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_500.000.pdf',
                'dip' => '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION A - 20232311AVN2634-LB.docx.pdf',
                'dip_aggiuntivo' => '/storage/contracts/DIP414_dip aggiuntivo section a2311AVN2553-LB.docx.pdf',
            )
        );

        DB::table('contracts')->insert(
            array(
                'id' => '12',
                'contract_filepath' => '/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_500.000.pdf',
                'dip' => '/storage/contracts/DIP414_DIP DANNI COLPA GRAVE SECTION B - 20232311AVN2648-LB.docx.pdf',
                'dip_aggiuntivo' => '/storage/contracts/DIP414_dip aggiuntivo section b-20232311AVN2608-LB.docx.pdf',
            )
        );

        DB::table('contracts')->insert(
            array(
                'id' => '13',
                'contract_filepath' => '/storage/contracts/AGGIORNATO_WORDING_GIOVANE_MEDICO_2024.pdf',
                'dip' => '/storage/contracts/DIP_GIOVANE_MEDICO.pdf',
                'dip_aggiuntivo' => '/storage/contracts/DIP_AGGIUNTIVO_GIOVANE_MEDICO.pdf',
            )
        );

        DB::statement("ALTER TABLE product_offers ADD old INT DEFAULT null");

        DB::table('product_offers')->insert(
            array(
                'id' => '165',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'New Line Doc 5mil - 2024',
                'sku' => '',
                'total_payable' => '480.99',
                'admin_fee' => '50.00',
                'gross_premium' => '430.99',
                'tax' => '78.44',
                'net_premium' => '320.50',
                'accessory' => '32.05',
                'net_plus_access_premium' => '352.55',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '37'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '166',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'New Line Doc 2 mil - 2024',
                'sku' => '',
                'total_payable' => '413.08',
                'admin_fee' => '50.00',
                'gross_premium' => '363.08',
                'tax' => '66.08',
                'net_premium' => '270.00',
                'accessory' => '27.00',
                'net_plus_access_premium' => '297.00',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '38'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '167',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'New Line 1 mil Doc 2024',
                'sku' => '',
                'total_payable' => '372.74',
                'admin_fee' => '50.00',
                'gross_premium' => '322.74',
                'tax' => '58.74',
                'net_premium' => '240.00',
                'accessory' => '24.00',
                'net_plus_access_premium' => '264.00',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '39'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '168',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Med Conv 5000000 - 2024',
                'sku' => '',
                'total_payable' => '480.99',
                'admin_fee' => '50.00',
                'gross_premium' => '430.99',
                'tax' => '78.44',
                'net_premium' => '320.50',
                'accessory' => '32.05',
                'net_plus_access_premium' => '352.55',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '40'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '169',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Med Conv 2000000 - 2024',
                'sku' => '',
                'total_payable' => '413.08',
                'admin_fee' => '50.00',
                'gross_premium' => '363.08',
                'tax' => '66.08',
                'net_premium' => '270.00',
                'accessory' => '27.00',
                'net_plus_access_premium' => '297.00',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '41'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '170',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Med Conv 1000000 - 2024',
                'sku' => '',
                'total_payable' => '372.74',
                'admin_fee' => '50.00',
                'gross_premium' => '322.74',
                'tax' => '58.74',
                'net_premium' => '240.00',
                'accessory' => '24.00',
                'net_plus_access_premium' => '264.00',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '42'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '171',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Speciali 5000000 - 2024',
                'sku' => '',
                'total_payable' => '337.10',
                'admin_fee' => '50.00',
                'gross_premium' => '287.10',
                'tax' => '52.25',
                'net_premium' => '213.50',
                'accessory' => '21.35',
                'net_plus_access_premium' => '234.85',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '43'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '172',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Speciali 2000000 - 2024',
                'sku' => '',
                'total_payable' => '294.74',
                'admin_fee' => '50.00',
                'gross_premium' => '244.74',
                'tax' => '44.54',
                'net_premium' => '182.00',
                'accessory' => '18.20',
                'net_plus_access_premium' => '200.20',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '44'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '173',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal Speciali 1000000 - 2024',
                'sku' => '',
                'total_payable' => '265.83',
                'admin_fee' => '50.00',
                'gross_premium' => '215.83',
                'tax' => '39.28',
                'net_premium' => '160.50',
                'accessory' => '16.05',
                'net_plus_access_premium' => '176.55',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '45'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '174',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal PS 5000000 - 2024',
                'sku' => '',
                'total_payable' => '85.51',
                'admin_fee' => '25.00',
                'gross_premium' => '60.51',
                'tax' => '11.01',
                'net_premium' => '45.00',
                'accessory' => '4.50',
                'net_plus_access_premium' => '49.50',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '46'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '175',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal PS 2000000 - 2024',
                'sku' => '',
                'total_payable' => '76.10',
                'admin_fee' => '25.00',
                'gross_premium' => '51.10',
                'tax' => '9.30',
                'net_premium' => '38.00',
                'accessory' => '3.80',
                'net_plus_access_premium' => '41.80',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '47'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '176',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal PS 1000000 - 2024',
                'sku' => '',
                'total_payable' => '70.72',
                'admin_fee' => '25.00',
                'gross_premium' => '45.72',
                'tax' => '8.32',
                'net_premium' => '34.00',
                'accessory' => '3.40',
                'net_plus_access_premium' => '37.40',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '48'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '177',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal NO Doctors 5000000 - 2024',
                'sku' => '',
                'total_payable' => '305.50',
                'admin_fee' => '50.00',
                'gross_premium' => '255.50',
                'tax' => '46.50',
                'net_premium' => '190.00',
                'accessory' => '19.00',
                'net_plus_access_premium' => '209.00',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '49'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '178',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal NO Doctors 2000000 - 2024',
                'sku' => '',
                'total_payable' => '267.18',
                'admin_fee' => '50.00',
                'gross_premium' => '217.18',
                'tax' => '39.53',
                'net_premium' => '161.50',
                'accessory' => '16.15',
                'net_plus_access_premium' => '177.65',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '50'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '179',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal NO Doctors 1000000 - 2024',
                'sku' => '',
                'total_payable' => '242.30',
                'admin_fee' => '50.00',
                'gross_premium' => '192.30',
                'tax' => '35.00',
                'net_premium' => '143.00',
                'accessory' => '14.30',
                'net_plus_access_premium' => '157.30',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '51'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '180',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal DF 5000000 - 2024',
                'sku' => '',
                'total_payable' => '150.06',
                'admin_fee' => '25.00',
                'gross_premium' => '125.06',
                'tax' => '22.76',
                'net_premium' => '93.00',
                'accessory' => '9.30',
                'net_plus_access_premium' => '102.30',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '52'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '181',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal DF 2000000 - 2024',
                'sku' => '',
                'total_payable' => '127.87',
                'admin_fee' => '25.00',
                'gross_premium' => '102.87',
                'tax' => '18.72',
                'net_premium' => '76.50',
                'accessory' => '7.65',
                'net_plus_access_premium' => '84.15',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '53'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '182',
                'product_id' => '1',
                'contract_id' => '12',
                'name' => 'MedMal DF 1000000 - 2024',
                'sku' => '',
                'total_payable' => '117.12',
                'admin_fee' => '25.00',
                'gross_premium' => '92.12',
                'tax' => '16.77',
                'net_premium' => '68.50',
                'accessory' => '6.85',
                'net_plus_access_premium' => '75.35',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '54'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '183',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'New Line Doc 5mil Private - 2024',
                'sku' => '',
                'total_payable' => '766.08',
                'admin_fee' => '50.00',
                'gross_premium' => '716.08',
                'tax' => '130.33',
                'net_premium' => '532.50',
                'accessory' => '53.25',
                'net_plus_access_premium' => '585.75',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '55'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '184',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'New Line Doc 2mil Private - 2024',
                'sku' => '',
                'total_payable' => '659.17',
                'admin_fee' => '50.00',
                'gross_premium' => '609.17',
                'tax' => '110.87',
                'net_premium' => '453.00',
                'accessory' => '45.30',
                'net_plus_access_premium' => '498.30',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '56'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '185',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'New Line 1 mil Doc Private - 2024',
                'sku' => '',
                'total_payable' => '586.56',
                'admin_fee' => '50.00',
                'gross_premium' => '536.56',
                'tax' => '97.66',
                'net_premium' => '399.00',
                'accessory' => '39.90',
                'net_plus_access_premium' => '438.90',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '57'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '186',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Med Conv 5000000 Private - 2024',
                'sku' => '',
                'total_payable' => '766.08',
                'admin_fee' => '50.00',
                'gross_premium' => '716.08',
                'tax' => '130.33',
                'net_premium' => '532.50',
                'accessory' => '53.25',
                'net_plus_access_premium' => '585.75',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '58'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '187',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Med Conv 2000000 Private - 2024',
                'sku' => '',
                'total_payable' => '659.17',
                'admin_fee' => '50.00',
                'gross_premium' => '609.17',
                'tax' => '110.87',
                'net_premium' => '453.00',
                'accessory' => '45.30',
                'net_plus_access_premium' => '498.30',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '59'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '188',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Med Conv 1000000 Private - 2024',
                'sku' => '',
                'total_payable' => '586.56',
                'admin_fee' => '50.00',
                'gross_premium' => '536.56',
                'tax' => '97.66',
                'net_premium' => '399.00',
                'accessory' => '39.90',
                'net_plus_access_premium' => '438.90',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '60'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '189',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Speciali 5000000 Private - 2024',
                'sku' => '',
                'total_payable' => '463.51',
                'admin_fee' => '50.00',
                'gross_premium' => '413.51',
                'tax' => '75.26',
                'net_premium' => '307.50',
                'accessory' => '30.75',
                'net_plus_access_premium' => '338.25',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '61'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '190',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Speciali 2000000 Private - 2024',
                'sku' => '',
                'total_payable' => '401.65',
                'admin_fee' => '50.00',
                'gross_premium' => '351.65',
                'tax' => '64.00',
                'net_premium' => '261.50',
                'accessory' => '26.15',
                'net_plus_access_premium' => '287.65',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '62'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '191',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal Speciali 1000000 Private - 2024',
                'sku' => '',
                'total_payable' => '360.64',
                'admin_fee' => '50.00',
                'gross_premium' => '310.64',
                'tax' => '56.54',
                'net_premium' => '231.00',
                'accessory' => '23.10',
                'net_plus_access_premium' => '254.10',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '63'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '192',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal PS 5000000 Private - 2024',
                'sku' => '',
                'total_payable' => '109.05',
                'admin_fee' => '25.00',
                'gross_premium' => '84.05',
                'tax' => '15.30',
                'net_premium' => '62.50',
                'accessory' => '6.25',
                'net_plus_access_premium' => '68.75',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '64'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '193',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal PS 2000000 Private - 2024',
                'sku' => '',
                'total_payable' => '98.29',
                'admin_fee' => '25.00',
                'gross_premium' => '73.29',
                'tax' => '13.34',
                'net_premium' => '54.50',
                'accessory' => '5.45',
                'net_plus_access_premium' => '59.95',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '65'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '194',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal PS 1000000 Private - 2024',
                'sku' => '',
                'total_payable' => '89.55',
                'admin_fee' => '25.00',
                'gross_premium' => '64.55',
                'tax' => '11.75',
                'net_premium' => '48.00',
                'accessory' => '4.80',
                'net_plus_access_premium' => '52.80',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '66'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '195',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal NO Doctors 5000000 Private - 2024',
                'sku' => '',
                'total_payable' => '884.42',
                'admin_fee' => '50.00',
                'gross_premium' => '834.42',
                'tax' => '151.87',
                'net_premium' => '620.50',
                'accessory' => '62.05',
                'net_plus_access_premium' => '682.55',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '67'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '196',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal NO Doctors 2000000 Private - 2024',
                'sku' => '',
                'total_payable' => '759.36',
                'admin_fee' => '50.00',
                'gross_premium' => '709.36',
                'tax' => '129.11',
                'net_premium' => '527.50',
                'accessory' => '52.75',
                'net_plus_access_premium' => '580.25',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '68'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '197',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal NO Doctors 1000000 Private - 2024',
                'sku' => '',
                'total_payable' => '675.98',
                'admin_fee' => '50.00',
                'gross_premium' => '625.98',
                'tax' => '113.93',
                'net_premium' => '465.50',
                'accessory' => '46.55',
                'net_plus_access_premium' => '512.05',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '69'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '198',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal DF 5000000 Private - 2024',
                'sku' => '',
                'total_payable' => '189.73',
                'admin_fee' => '25.00',
                'gross_premium' => '164.73',
                'tax' => '29.98',
                'net_premium' => '122.50',
                'accessory' => '12.25',
                'net_plus_access_premium' => '134.75',
                'description' => 'vuoto',
                'indemnity_limit' => '5000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '70'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '199',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal DF 2000000 Private - 2024',
                'sku' => '',
                'total_payable' => '164.18',
                'admin_fee' => '25.00',
                'gross_premium' => '139.18',
                'tax' => '25.33',
                'net_premium' => '103.50',
                'accessory' => '10.35',
                'net_plus_access_premium' => '113.85',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '71'
            )
        );

DB::table('product_offers')->insert(
            array(
                'id' => '200',
                'product_id' => '1',
                'contract_id' => '11',
                'name' => 'MedMal DF 1000000 Private - 2024',
                'sku' => '',
                'total_payable' => '150.06',
                'admin_fee' => '25.00',
                'gross_premium' => '125.06',
                'tax' => '22.76',
                'net_premium' => '93.00',
                'accessory' => '9.30',
                'net_plus_access_premium' => '102.30',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '500000',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
		'created_at' => now(),
		'updated_at' => now(),
		'old' => '72'
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
