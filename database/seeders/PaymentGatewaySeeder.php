<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentGateway::factory()->count(3)->sequence(
            ['name' => 'Paypal', 'public' => 1],
            ['name' => 'BBM', 'public' => 1],
            ['name' => 'Office Payment', 'public' => 0],
        )->create();
    }
}
