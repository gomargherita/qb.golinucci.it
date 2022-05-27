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

        ProductStep::factory()->count(7)->sequence(
            [
                'product_id' => 1,
                'step_number' => 1,
                'step_name' => 'Anagrafica'
            ],
            [
                'product_id' => 1,
                'step_number' => 2,
                'step_name' => 'Scelta offerta prodotto'
            ],
            [
                'product_id' => 1,
                'step_number' => 3,
                'step_name' => 'Dichiarazioni'
            ],
            [
                'product_id' => 1,
                'step_number' => 4,
                'step_name' => 'Pagamento'
            ],
            [
                'product_id' => 1,
                'step_number' => 5,
                'step_name' => 'Lloyds'
            ],
            [
                'product_id' => 1,
                'step_number' => 6,
                'step_name' => 'Yousign'
            ],
            [
                'product_id' => 1,
                'step_number' => 7,
                'step_name' => 'Completato'
            ],
        )->create();
    }
}
