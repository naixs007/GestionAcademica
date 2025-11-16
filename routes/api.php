<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Api\AsistenciaController as ApiAsistenciaController;

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

/**
 * Rutas de API para Marcado de Asistencia de Docentes
 * Protegidas por middleware de autenticación
 */
Route::middleware(['auth'])->group(function () {
    // GET /api/asistencia/marcado/{horarioId} - Obtener datos para marcado
    Route::get('/asistencia/marcado/{horarioId}', [ApiAsistenciaController::class, 'getMarcadoData']);

    // POST /api/asistencia/registrar-docente - Registrar asistencia del docente
    Route::post('/asistencia/registrar-docente', [ApiAsistenciaController::class, 'registrarAsistenciaDocente']);
});

