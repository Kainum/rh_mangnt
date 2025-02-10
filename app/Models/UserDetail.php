<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    
    protected $fillable = [
        'address',
        'zip_code',
        'city',
        'phone',
        'salary',
        'admission_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
