<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ProductTypeSeeder::class,
            ConditionalFieldSeeder::class,
            ProductSeeder::class,
            ProductOfferSeeder::class,
            ProductStepSeeder::class,
            ProductDeclarationSeeder::class,
        ]);
    }
}
