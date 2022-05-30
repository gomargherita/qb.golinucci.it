<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ClientDetail;
use App\Models\Role;
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

        $user = User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Marco',
            'last_name' => 'Masi',
            'email' => 'masiorama@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'owner' => true,
        ]);

        ClientDetail::factory(1)
            ->create([
                'user_id' => $user->id,
                'first_name' => 'Camillo',
                'last_name' => 'Campana',
                'fiscal_code' => 'CMPCLL09M11C351C',
                'phone' => '3299858148'
            ]);

        $user = User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'fiscal_code' => 'RSSMRA80A02C573I',
            'owner' => true,
        ]);

        ClientDetail::factory(1)
            ->create([
                'user_id' => $user->id,
                'phone' => '3299858148'
            ]);

        // create clients
        // $users = User::factory(21)->create([
        //     'account_id' => $account->id,
        //     'owner' => false,
        // ]);

        // assign multiple clientDetails to prev clients
        // ClientDetail::factory(40)
        //     ->create()
        //     ->each(function ($clientDetail) use ($users) {
        //         $clientDetail->update(['user_id' => $users->random()->id]);
        //     });

        // // new account for agents
        // $account_ext = Account::create(['name' => 'Prisma Broker']);

        // User::factory()->create([
        //     'account_id' => $account_ext->id,
        //     'email' => 'masiorama+prisma@gmail.com',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        //     'owner' => true,
        // ]);

        // // create agent clients
        // $users_ext = User::factory(21)->create([
        //     'account_id' => $account_ext->id,
        //     'owner' => false,
        // ]);

        // ClientDetail::factory(25)
        //     ->create()
        //     ->each(function ($clientDetail) use ($users_ext) {
        //         $clientDetail->update(['user_id' => $users_ext->random()->id]);
        //     });


        // first will be our superadmin
        $users = User::all();
        $users->map(function ($user) {
            switch ($user->id) {
                case 1:
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_SUPERADMIN);
                    break;
                case 2:
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_OPERATOR);
                    break;
                // case 25:
                //     setPermissionsTeamId(2);
                //     $user->assignRole(Role::ROLE_AGENT);
                    break;
                default:
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_USER);
            }
        });
    }
}
