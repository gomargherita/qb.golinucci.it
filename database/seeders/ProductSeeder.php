<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->create([
            'name' => 'Polizza Singola Medici e Personale Sanitario',
            'account_id' => '1',
            'hex_color' => '#6CB8EB',
        ]);

        Product::factory(3)
            ->create([

            ]);
    }
}
