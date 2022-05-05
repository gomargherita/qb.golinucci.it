<?php

namespace Database\Seeders;

use App\Models\ProductStep;
use Illuminate\Database\Seeder;

class ProductStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ProductStep::factory()->count(6)->sequence(
            [
                'product_id' => 1,
                'step_number' => 1
            ],
            [
                'product_id' => 1,
                'step_number' => 2
            ],
            [
                'product_id' => 1,
                'step_number' => 3
            ],
            [
                'product_id' => 1,
                'step_number' => 4
            ],
            [
                'product_id' => 1,
                'step_number' => 5
            ],
            [
                'product_id' => 1,
                'step_number' => 6
            ],
        )->create();
    }
}
