<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/home');
    Route::view('/home', 'home')->name('home');

    // user profile
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile/update-password', [ProfileController::class, 'updatePassword'])->name('user.profile.update_password');
    Route::post('/user/profile/update-data', [ProfileController::class, 'updateData'])->name('user.profile.update_data');

    // departments
    Route::prefix('/departments')->name('departments.')->controller(DepartmentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
    });

    // RH colaborators
    Route::prefix('/colaborators/rh')->name('colaborators.rh.')->controller(RhUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
    });
});