<?php

namespace Database\Factories;

use App\Models\PaymentGateway;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products_number = Product::count();
        $payment_gateways_number = PaymentGateway::count();

        return [
            'product_id' => $this->faker->numberBetween(1, $products_number),
            'payment_gateway_id' => $this->faker->numberBetween(1, $payment_gateways_number),
            'counter' => 0,
            'prefix' => strtoupper($this->faker->lexify('???')),
        ];
    }
}
