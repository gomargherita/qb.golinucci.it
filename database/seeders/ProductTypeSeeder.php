<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductType::factory()->create([
            'name' => 'Medici',
        ]);

        ProductType::factory()->create([
            'name' => 'Bancari',
        ]);
    }
}
