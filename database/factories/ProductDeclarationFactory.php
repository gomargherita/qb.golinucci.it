<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDeclarationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product_number = Product::count();

        return [
            'product_id' => $this->faker->numberBetween(1, $product_number),
            'declaration' => $this->faker->paragraph(1),
            'notes' => $this->faker->paragraph(random_int(1, 2)),
            'sort' => $this->faker->numberBetween(1, 10),
        ];
    }
}
