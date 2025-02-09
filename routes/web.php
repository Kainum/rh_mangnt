<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/home');
    Route::view('/home', 'home')->name('home');
});