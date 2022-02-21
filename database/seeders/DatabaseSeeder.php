<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\UserDetail;
use App\Models\User;
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
        $account = Account::create(['name' => 'Golinucci']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Marco',
            'last_name' => 'Masi',
            'email' => 'masiorama@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'owner' => true,
        ]);

        User::factory(5)->create(['account_id' => $account->id]);

        $userDetails = UserDetail::factory(100)
            ->create(['account_id' => $account->id]);

        Contact::factory(100)
            ->create(['account_id' => $account->id])
            ->each(function ($contact) use ($userDetails) {
                $contact->update(['user_detail_id' => $userDetails->random()->id]);
            });
    }
}
