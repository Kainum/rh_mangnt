<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // -------------------
        // GATES
        // -------------------

        // Define uma gate que checa se o usuário pe admin
        Gate::define('admin', function () {
            return Auth::user()->role == 'admin';
        });
    }
}
