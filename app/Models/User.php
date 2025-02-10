<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;

class User extends AuthUser
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'confirmation_token',
        'role',
        'permissions',
        'department_id',
    ];

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
