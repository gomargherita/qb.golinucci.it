<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentType::factory()->count(3)->sequence(
            ['name' => 'Certificati'],
            ['name' => 'Dichiarazioni'],
            ['name' => 'Firme Semplici'],
        )->create();
    }
}
