<?php

namespace Database\Seeders;

use App\Models\ConditionalField;
use Illuminate\Database\Seeder;

class ConditionalFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConditionalField::factory()->create([
            'field_name' => 'Professioni Medici',
            'field_type' => 'select',
            'field_value' => json_encode([
                1 => 'Dirigente Medico',
                2 => 'Medico Convenzionato',
                3 => 'Medico Specialista in Formazione',
                4 => 'Personale sanitario',
                5 => 'Dirigente Sanitario non Medico',
                6 => 'Dirigente Infermieristico'
            ]),
        ]);

        ConditionalField::factory()->create([
            'field_name' => 'Ente di Appartenenza',
            'field_type' => 'select',
            'field_value' => json_encode([
                1 => 'Pubblico/ASL',
                2 => 'Privato'
            ]),
        ]);
    }
}
