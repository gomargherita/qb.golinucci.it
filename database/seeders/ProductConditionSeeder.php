<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCondition;
use Illuminate\Database\Seeder;

class ProductConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCondition::factory()->create([
            'product_id' => 1,
            'conditional_field_id' => 1
        ]);

        ProductCondition::factory()->create([
            'product_id' => 1,
            'conditional_field_id' => 2
        ]);

    }
}
