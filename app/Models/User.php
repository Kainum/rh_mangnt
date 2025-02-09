<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
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
