<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

//ruta panel administrador
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// redirigir /admin a admin.dashboard (comodidad)
Route::redirect('/admin', '/admin/dashboard')->middleware(['auth','verified']);

// Rutas admin: usuarios (resource)
Route::prefix('admin')->middleware(['auth','verified'])->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->names('users');
});

//ruta panel decano
Route::get('/decano/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('decano.dashboard');

//ruta panel docente
Route::get('/docente/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('docente.dashboard');


// Ruta /dashboard unificada: redirige según rol (mejora compatibilidad con templates)
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user) return redirect('/');
    if ($user->hasAnyRole(['admin','super-admin']) && Route::has('admin.dashboard')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('decano') && Route::has('decano.dashboard')) {
        return redirect()->route('decano.dashboard');
    }
    if ($user->hasRole('docente') && Route::has('docente.dashboard')) {
        return redirect()->route('docente.dashboard');
    }
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
