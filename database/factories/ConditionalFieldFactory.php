<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionalFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'field_name' => '',
            'field_label' => '',
            'field_type' => '',
            'field_value' => '',
        ];
    }
}
