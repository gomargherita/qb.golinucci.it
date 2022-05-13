<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numerify,
            'phone' => $this->faker->tollFreePhoneNumber,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'fiscal_code' => $this->faker->bothify('??????##?##?###?'),
            'gender' => $this->faker->randomElement(['M' ,'F']),
            'dob'=> $this->faker->date(),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'country' => 'IT',
            'postal_code' => $this->faker->bothify('#####'),
            'confirmation' => false,
            'contact_ref' => null,
            'version_ref' => null,
            'note' => null,
            'with_accident' => 0,
            'flag_prev_2022' => 0
        ];
    }
}
