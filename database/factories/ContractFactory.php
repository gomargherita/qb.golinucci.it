<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_filepath' => $this->faker->filePath(), //(storage_path('files/uploads'), public_path('files/uploads'), false),
            'contract_prop' => null
        ];
    }
}
