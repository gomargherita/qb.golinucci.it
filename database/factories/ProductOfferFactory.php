<?php

namespace Database\Factories;

use App\Models\ConditionalField;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products_number = Product::count();
        $conditional_fields_number = ConditionalField::count();

        return [
            'name' => $this->faker->unique()->domainName(),
            'sku' => $this->faker->ean13(),
            'product_id' => $this->faker->numberBetween(1, $products_number),
            'total_payable' => $this->faker->numberBetween(0, 30000),
            'admin_fee' => $this->faker->numberBetween(0, 30000),
            'price_with_tax' => $this->faker->numberBetween(0, 30000),
            'tax' => $this->faker->numberBetween(0, 30000),
            'price' => $this->faker->numberBetween(0, 30000),
            'description' => $this->faker->paragraph(3),
            'indemnity_limit' => $this->faker->numberBetween(0, 10000000),
            'months' => $this->faker->numberBetween(1, 12),
            'active' => $this->faker->boolean(),
        ];
    }
}
