<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products_number = Product::count();

        return [
            'product_id' => $this->faker->numberBetween(1, $products_number),
            'step_number' => $this->faker->numberBetween(1, 6)
        ];
    }
}
