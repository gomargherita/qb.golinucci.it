<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use HasFactory;

    const PERM_VIEW_USERS = 'view_users';
    const PERM_ADD_USERS = 'add_users';
    const PERM_EDIT_USERS = 'edit_users';
    const PERM_DELETE_USERS = 'delete_users';

    const PERM_VIEW_ROLES = 'view_roles';
    const PERM_ADD_ROLES = 'add_roles';
    const PERM_EDIT_ROLES = 'edit_roles';
    const PERM_DELETE_ROLES = 'delete_roles';

    public static function defaultPermissions()
    {
        return [
            self::PERM_VIEW_USERS,
            self::PERM_ADD_USERS,
            self::PERM_EDIT_USERS,
            self::PERM_DELETE_USERS,

            self::PERM_VIEW_ROLES,
            self::PERM_ADD_ROLES,
            self::PERM_EDIT_ROLES,
            self::PERM_DELETE_ROLES,
        ];
    }
}
