<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfiguracionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Rutas de Configuración del Sistema (CU23)
 * Protegidas por middleware de autenticación y roles de administrador
 */
Route::middleware(['auth', 'role:Administrador'])->group(function () {
    // GET /api/configuraciones - Obtener configuración actual
    Route::get('/configuraciones', [ConfiguracionController::class, 'show']);

    // POST /api/configuraciones - Actualizar configuración
    Route::post('/configuraciones', [ConfiguracionController::class, 'apiUpdate']);
});
