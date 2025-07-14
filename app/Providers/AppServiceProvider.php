<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Aucun binding custom pour 'role.check' ici
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
