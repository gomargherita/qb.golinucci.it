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
            'field_name' => 'qualification',
            'field_label' => 'Qualifica dell\'Assicurato',
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
            'field_name' => 'authority_type',
            'field_label' => 'Tipologia Ente',
            'field_type' => 'select',
            'field_value' => json_encode([
                1 => 'Pubblico',
                2 => 'Privato-Pubblico'
            ]),
        ]);

        ConditionalField::factory()->create([
            'field_name' => 'authority',
            'field_label' => 'Ente di Appartenenza',
            'field_type' => 'text',
            'field_value' => '',
            'only_client_details' => true
        ]);

        ConditionalField::factory()->create([
            'field_name' => 'specialization',
            'field_label' => 'Specializzazione',
            'field_type' => 'text',
            'field_value' => '',
            'only_client_details' => true
        ]);
        
    }
}
