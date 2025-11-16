<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\HabilitacionAsistencia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        if (config('app.env') === 'production' || env('FORCE_HTTPS')) {
             URL::forceScheme('https');
        }

        // Binding explícito para habilitaciones
        Route::bind('habilitacion', function ($value) {
            return HabilitacionAsistencia::findOrFail($value);
        });
    }
}
