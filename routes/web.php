<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboratorController;
use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhManagementController;
use App\Http\Controllers\RhUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // home
    Route::redirect('/', '/home');
    Route::get('/home', function () {
        if (Auth::user()->can('admin')) {
            return redirect()->route('admin.home');
        } else if (Auth::user()->can('rh')) {
            return redirect()->route('rh.management.index');
        } else {
            return redirect()->route('colaborator.home');
        }
    })->name('home');

    // user profile
    Route::prefix('/user/profile')->name('user.profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update-password', 'updatePassword')->name('update_password');
        Route::post('/update-data', 'updateData')->name('update_data');
        Route::post('/update-address', 'updateAddress')->name('update_address');
    });

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

    // General colaborators
    Route::prefix('/rh/management')->name('rh.management.')->controller(RhManagementController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
        Route::get('/restore/{id}', 'restore')->name('restore');
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
        Route::get('/restore/{id}', 'restore')->name('restore');
    });

    // Colaborators
    Route::prefix('/colaborators')->name('colaborators.admin.')->controller(ColaboratorController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/delete-confirm/{id}', 'destroy')->name('destroy');
        Route::get('/restore/{id}', 'restore')->name('restore');
    });

    // Admin routes
    Route::get('/admin/home', [AdminController::class, 'home'])->name('admin.home');
    
    // Colaborator routes
    Route::get('/colaborator', [ColaboratorController::class, 'home'])->name('colaborator.home');


});

Route::middleware('guest')->group(function () {
    // email confirmation and password definition
    Route::get('/confirm-account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm_account');
    Route::post('/confirm-account', [ConfirmAccountController::class, 'confirmAccountSubmit'])->name('confirm_account_submit');
});
