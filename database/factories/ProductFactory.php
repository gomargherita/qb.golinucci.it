<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $accounts_number = Account::count();

        return [
            'name' => $this->faker->unique()->domainName(),
            'account_id' => $this->faker->numberBetween(1, $accounts_number),
            'description' => $this->faker->paragraph(3),
            'icon' => $this->faker->image(),
            'hex_color' => $this->faker->hexColor(),
        ];
    }
}
