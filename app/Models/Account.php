<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userDetails()
    {
        return $this->hasMany(UserDetail::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
