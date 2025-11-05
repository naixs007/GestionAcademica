<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Redirección según rol (usando Spatie)
        // Soportar tanto 'admin' como 'super-admin' (dependiendo de cómo se sembraron los roles)
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('decano')) {
            return redirect()->route('decano.dashboard');
        }

        if ($user->hasRole('docente')) {
            return redirect()->route('docente.dashboard');
        }

        // Si no tiene rol asignado o no matchea, calcula un fallback seguro
        // Prioriza rutas por rol si existen, si no usa la ruta 'dashboard' si está disponible, sino '/'.
        $defaultUrl = '/';
        if ($user) {
            if ($user->hasAnyRole(['admin', 'super-admin']) && Route::has('admin.dashboard')) {
                $defaultUrl = route('admin.dashboard');
            } elseif ($user->hasRole('decano') && Route::has('decano.dashboard')) {
                $defaultUrl = route('decano.dashboard');
            } elseif ($user->hasRole('docente') && Route::has('docente.dashboard')) {
                $defaultUrl = route('docente.dashboard');
            } elseif (Route::has('dashboard')) {
                $defaultUrl = route('dashboard');
            }
        } elseif (Route::has('dashboard')) {
            $defaultUrl = route('dashboard');
        }

        return redirect()->intended($defaultUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
