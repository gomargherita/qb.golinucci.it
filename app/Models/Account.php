<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // https://spatie.be/docs/laravel-permission/v5/basic-usage/teams-permissions
    public static function boot()
    {
        parent::boot();

        // here assign this team to a global user with global default role
        self::created(function ($model) {
            // get session account_id for restore it later
            $session_account_id = getPermissionsTeamId();
            // set actual new account_id to package instance
            setPermissionsTeamId($model);
            // get the admin user and assign roles/permissions on new team model
            User::find(1)->assignRole(Role::ROLE_SUPERADMIN);
            // restore session account_id to package instance
            setPermissionsTeamId($session_account_id);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientDetails()
    {
        return $this->hasManyThrough(ClientDetail::class, User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
