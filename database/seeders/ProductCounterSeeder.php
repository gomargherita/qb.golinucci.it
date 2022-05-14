<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\ProductCounter;
use Illuminate\Database\Seeder;

class ProductCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $payment_gateways = PaymentGateway::all();
        // 1 paypal, 2 bbm, 3 cassa

        ProductCounter::factory()->count(3)->sequence(
            ['product_id' => 1, 'payment_gateway_id' => 1, 'year' => 2022, 'counter' => 0, 'prefix' => '22NLP-'],
            ['product_id' => 1, 'payment_gateway_id' => 2, 'year' => 2022, 'counter' => 0, 'prefix' => '22NLB-'],
            ['product_id' => 1, 'payment_gateway_id' => 3, 'year' => 2022, 'counter' => 0, 'prefix' => '22NLC-'],
        )->create();
    }
}
