<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // permissions
        $permission_labels = Permission::defaultPermissions();
        $permissions = collect($permission_labels)->map(function ($permission_label) {
            return Permission::create([
                'name' => $permission_label
            ]);
        });

        $role_labels = Role::defaultRoles();
        collect($role_labels)
            ->map(function ($role_label) use ($permissions) {
                $role = Role::create([
                    'name' => $role_label,
                    'account_id' => ($role_label !== Role::ROLE_AGENT) ? 1 : 2
                ]);
                if ($role_label !== Role::ROLE_SUPERADMIN) {
                    $role->syncPermissions($permissions);
                }
            });

        // first will be our superadmin
        $users = User::all();
        $users->map(function ($user) {
            switch ($user->id) {
                case 1:
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_SUPERADMIN);
                    break;
                case 2:
                case 3:
                    setPermissionsTeamId(1);
                    $user->assignRole(Role::ROLE_OPERATOR);
                    break;
                case 4:
                case 5:
                    setPermissionsTeamId(2);
                    $user->assignRole(Role::ROLE_AGENT);
                    break;
            }
        });
    }
}
