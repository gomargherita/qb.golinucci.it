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
            // AccountSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            ProductTypeSeeder::class,
            ConditionalFieldSeeder::class,
            ProductSeeder::class,
            PaymentGatewaySeeder::class,
            ProductCounterSeeder::class,
            ContractSeeder::class,
            ProductOfferSeeder::class,
            ProductStepSeeder::class,
            ProductDeclarationSeeder::class,
            DoprintCounterSeeder::class,
            UserMedGiugno2022Seeder::class
        ]);
    }
}
