<?php

namespace App\Services;

use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Operations {

    public static function decryptId($value) {
        try {
            $value = Crypt::decrypt($value);
        } catch (DecryptException $e) {
            abort(403);
        }
        return $value;
    }

}