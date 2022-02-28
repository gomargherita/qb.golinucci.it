<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\ClientDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'Golinucci']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Marco',
            'last_name' => 'Masi',
            'email' => 'masiorama@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'owner' => true,
        ]);

        User::factory(2)->create(['account_id' => $account->id]);

        $clientDetails = ClientDetail::factory(40)
            ->create(['account_id' => $account->id]);

        Contact::factory(50)
            ->create(['account_id' => $account->id])
            ->each(function ($contact) use ($clientDetails) {
                $contact->update(['client_detail_id' => $clientDetails->random()->id]);
            });

        $account_ext = Account::create(['name' => 'Prisma Broker']);

        User::factory()->create([
            'account_id' => $account_ext->id,
            'email' => 'masiorama+prisma@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'owner' => false,
        ]);

        $clientDetails_ext = ClientDetail::factory(10)
            ->create(['account_id' => $account_ext->id]);

        Contact::factory(50)
            ->create(['account_id' => $account_ext->id])
            ->each(function ($contact) use ($clientDetails_ext) {
                $contact->update(['client_detail_id' => $clientDetails_ext->random()->id]);
            });
    }
}
