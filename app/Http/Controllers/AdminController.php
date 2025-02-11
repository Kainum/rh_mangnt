<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    
    public function home (): View {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        return view('home');
    }
}
