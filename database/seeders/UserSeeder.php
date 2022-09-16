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
     * @author  Marco Masi <masiorama@gmail.com>
     * @author  Maurizio Fonte (revised to remove old references to old programmer)
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'Golinucci']);

        // create the first Super Admin user and its details
        $user = User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Super', // it was "Marco"
            'last_name' => 'Admin', // it was "Masi"
            'email' => 'admin@example.com', // it was "masiorama@gmail.com"
            'owner' => true,
        ]);
        ClientDetail::factory(1)->create([
            'user_id' => $user->id,
            'first_name' => 'Camillo',
            'last_name' => 'Campana',
            'fiscal_code' => 'CMPCLL09M11C351C',
            'phone' => '3299858148'
        ]);

        // create the second Operator user and its details
        $user = User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'email' => 'posta@golinucci.it',
            'owner' => true,
        ]);
        ClientDetail::factory(1)->create([
            'user_id' => $user->id,
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'fiscal_code' => 'RSSMRA80A02C573I',
            'phone' => '3299858148',
        ]);

        // first will be our superadmin
        $users = User::all();
        $users->map(function ($user) {
            switch ($user->id) {
                case 1:
                    // Super Admin
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_SUPERADMIN);
                    break;
                case 2:
                    // Operator
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_OPERATOR);
                    break;
                /*
                case 25:
                    // Agents
                    setPermissionsTeamId(2);
                    $user->assignRole(Role::ROLE_AGENT);
                    break;
                */
                default:
                    // Normal Users
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_USER);
            }
        });
    }
}
