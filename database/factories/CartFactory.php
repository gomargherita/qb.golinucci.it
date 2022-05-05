<?php

namespace Database\Factories;

use App\Models\ProductOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users_number = User::count();
        $product_offers_number = ProductOffer::count();


        return [
            'user_id' => $this->faker->numberBetween(1, $users_number),
            'product_offer_id' => $this->faker->numberBetween(1, $product_offers_number),
            'cart_start_time' => $this->faker->dateTimeBetween('-1 year', '-6 months'),
            'cart_expiry_time' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'locked' => $this->faker->randomElement(['Y' ,'N']),
            'cart_ref' => $this->faker->bothify('*??***??****?'),
            'renewal_date' => $this->faker->dateTimeBetween('+1 year'),
            'total_payable' => $this->faker->numerify('###'),
            'admin_fee' => $this->faker->numerify('##'),
            'gross_premium' => $this->faker->numerify('###'),
            'tax' => $this->faker->numerify('##'),
            'net_premium' => $this->faker->numerify('###'),
            'accessory' => $this->faker->numerify('###'),
            'net_plus_access_premium' => $this->faker->numerify('###'),
            'indemnity_limit' => $this->faker->numerify('######'),
        ];
    }
}
