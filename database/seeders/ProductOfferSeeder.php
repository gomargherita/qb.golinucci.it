<?php

namespace Database\Seeders;

use App\Models\ProductOffer;
use Illuminate\Database\Seeder;

class ProductOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductOffer::factory()->create([
            'product_id' => 1,
            'name' => 'Polizza Singola Medici e Personale Sanitario',
            'sku' => 'ASDASD897897',
            'active' => 1
        ]);

        ProductOffer::factory()->create([
            'product_id' => 1,
            'name' => 'Polizza Singola Medici e Personale Sanitario 2',
            'sku' => 'ASDASD897897',
            'active' => 1
        ]);

        ProductOffer::factory()->create([
            'product_id' => 1,
            'name' => 'Polizza Singola Medici e Personale Sanitario 3',
            'sku' => 'ASDASD897897',
            'active' => 1
        ]);
    }
}
