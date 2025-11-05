<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        RedirectIfAuthenticated::redirectUsing(function (Request $request) {
            $user = $request->user();
            if (! $user) {
                return '/';
            }
            // usa los nombres de roles que tienes (ej: 'super-admin' o 'admin')
            if ($user->hasAnyRole(['admin', 'super-admin']) && Route::has('admin.dashboard')) {
                return route('admin.dashboard');
            }
            if ($user->hasRole('decano') && Route::has('decano.dashboard')) {
                return route('decano.dashboard');
            }
            if ($user->hasRole('docente') && Route::has('docente.dashboard')) {
                return route('docente.dashboard');
            }
            if (Route::has('dashboard')) {
                return route('dashboard'); // o '/': fallback
            }

            return '/';
        });
    }
}
