<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;

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

    public function getSalaryAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
