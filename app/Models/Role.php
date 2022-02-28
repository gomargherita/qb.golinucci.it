<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasFactory;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_OPERATOR = 'user_operator'; // golinucci operators
    const ROLE_AGENT = 'user_agent'; // external operators

    public static function defaultRoles()
    {
        return [
            self::ROLE_SUPERADMIN,
            self::ROLE_OPERATOR,
            self::ROLE_AGENT
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }
}
