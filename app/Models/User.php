<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
