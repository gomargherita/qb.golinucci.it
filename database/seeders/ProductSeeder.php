<?php

namespace Database\Seeders;

use App\Models\ConditionalField;
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
        $conditionalFields = ConditionalField::all();

        Product::factory()
        ->hasAttached($conditionalFields)
        ->create([
            'name' => 'Polizza Singola Medici e Personale Sanitario',
            'account_id' => '1',
            'product_type_id' => '1',
            'hex_color' => '#6CB8EB',
            'active' => 1
        ]);

        Product::factory(5)
            ->create();
    }
}
