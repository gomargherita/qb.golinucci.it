<?php

namespace Database\Factories;

use App\Models\ConditionalField;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductConditionFactory extends Factory
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
            'product_id' => $this->faker->numberBetween(1, $products_number),
            'conditional_field_id' => $this->faker->numberBetween(1, $conditional_fields_number),
        ];
    }
}
