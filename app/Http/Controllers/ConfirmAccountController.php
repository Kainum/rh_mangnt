<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmAccountController extends Controller
{
    public function confirmAccount($token)
    {
        echo 'estou aqui: ' . $token;
    }
}
