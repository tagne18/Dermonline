<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/redirect-after-login';

    public function boot()
    {
        parent::boot();

        // Redirection après login selon le rôle
        Route::middleware('web')->group(function () {
            Route::get('/redirect', function () {
                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'medecin') {
                    return redirect()->route('medecin.dashboard');
                } else {
                    return redirect()->route('profile.show'); // patient
                }
            });
        });
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }
    
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return '/admin/dashboard';
        } elseif ($user->role === 'medecin') {
            return '/medecin/dashboard';
        } else {
            return '/dashboard'; // patient
        }
    }
}
