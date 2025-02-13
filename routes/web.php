<?php

use App\Http\Controllers\ColaboratorController;
use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhColaboratorController;
use App\Http\Middleware\OnlyAdminMiddleware;
use App\Http\Middleware\OnlyRhMiddleware;
use App\Http\Middleware\OnlyRhOrAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Home
    Route::redirect('/', '/home');
    Route::get('/home', [HomeController::class, 'home'])->name('home');

    // User Profile
    Route::prefix('/my-profile')->name('user.profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update-password', 'updatePassword')->name('update_password');
        Route::post('/update-data', 'updateData')->name('update_data');
        Route::post('/update-address', 'updateAddress')->name('update_address');
    });

    // Colaborators
    Route::prefix('/colaborators')->name('colaborators.')->controller(ColaboratorController::class)->group(function () {

        Route::middleware([OnlyRhOrAdminMiddleware::class])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}', 'show')->name('show');             // Tanto para RH quanto geral
            Route::get('/delete/{id}', 'delete')->name('delete');
            Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
            Route::get('/restore/{id}', 'restore')->name('restore');    // Tanto para RH quanto geral
        });

        Route::middleware([OnlyRhMiddleware::class])->group(function () {
            Route::get('/new', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
        });
    });

    Route::middleware([OnlyAdminMiddleware::class])->group(function () {
        // RH colaborators
        Route::prefix('/colaborators/rh')->name('colaborators.rh.')->controller(RhColaboratorController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/new', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/delete/{id}', 'delete')->name('delete');
            Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
        });

        // Departments
        Route::prefix('/departments')->name('departments.')->controller(DepartmentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/new', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/delete/{id}', 'delete')->name('delete');
            Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
        });
    });
});

Route::middleware('guest')->group(function () {
    // email confirmation and password definition
    Route::get('/confirm-account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm_account');
    Route::post('/confirm-account', [ConfirmAccountController::class, 'confirmAccountSubmit'])->name('confirm_account_submit');
});
