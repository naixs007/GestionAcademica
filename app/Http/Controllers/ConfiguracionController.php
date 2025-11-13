<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Configuracion;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Validator;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the configuration parameters.
     */
    public function index()
    {
        // Obtener configuración actual del sistema (singleton)
        $configuracion = Configuracion::current();

        return view('admin.configuracion.index', compact('configuracion'));
    }

    /**
     * Show the form for editing configuration parameters.
     */
    public function edit()
    {
        // Obtener configuración actual del sistema (singleton)
        $configuracion = Configuracion::current();

        return view('admin.configuracion.edit', compact('configuracion'));
    }

    /**
     * Update the configuration parameters (web form).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nombre_institucion' => 'nullable|string|max:255',
            'logo_institucional_path' => 'nullable|string|max:255',
            'periodo_academico_default_id' => 'nullable|integer',
            'tolerancia_asistencia_minutos' => 'nullable|integer|min:0|max:60',
            'requerir_motivo_ausencia' => 'nullable|boolean',
            'expiracion_contrasena_dias' => 'nullable|integer|min:30|max:365',
            'notificaciones_email_remitente' => 'nullable|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Obtener configuración actual y actualizarla
            $configuracion = Configuracion::current();
            $configuracion->update($validated);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => 'El usuario ' . auth()->id() . ' actualizó los parámetros generales del sistema',
                'metodo' => 'PUT',
                'ruta' => route('admin.configuracion.update'),
                'direccion_ip' => $request->ip(),
                'navegador' => $request->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuración actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar configuraciones: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Get all configuration parameters (Deprecated - usar Configuracion::current()).
     *
     * @deprecated Use Configuracion::current() instead
     */
    private function getConfiguraciones()
    {
        // Retornar configuración actual usando el modelo
        return Configuracion::current()->toArray();
    }

    /**
     * Reset configuration to default values.
     */
    public function reset()
    {
        try {
            DB::beginTransaction();

            // Obtener configuración actual y resetear a valores por defecto
            $configuracion = Configuracion::current();
            $configuracion->update([
                'nombre_institucion' => 'Sistema de Gestión Académica',
                'logo_institucional_path' => null,
                'periodo_academico_default_id' => null,
                'tolerancia_asistencia_minutos' => 10,
                'requerir_motivo_ausencia' => false,
                'expiracion_contrasena_dias' => 90,
                'notificaciones_email_remitente' => null,
            ]);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => 'El usuario ' . auth()->id() . ' restableció los parámetros del sistema a valores por defecto',
                'metodo' => 'POST',
                'ruta' => route('admin.configuracion.reset'),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuración restablecida a valores por defecto.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al restablecer configuraciones: ' . $e->getMessage()]);
        }
    }

    /**
     * API: Obtener la configuración actual del sistema.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {
            $configuracion = Configuracion::current();

            return response()->json([
                'success' => true,
                'data' => $configuracion,
                'message' => 'Configuración obtenida exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Actualizar la configuración del sistema.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'nombre_institucion' => 'nullable|string|max:255',
            'logo_institucional_path' => 'nullable|string|max:255',
            'periodo_academico_default_id' => 'nullable|integer|exists:periodos_academicos,id',
            'tolerancia_asistencia_minutos' => 'nullable|integer|min:0|max:60',
            'requerir_motivo_ausencia' => 'nullable|boolean',
            'expiracion_contrasena_dias' => 'nullable|integer|min:30|max:365',
            'notificaciones_email_remitente' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Obtener configuración actual y actualizarla
            $configuracion = Configuracion::current();
            $configuracion->update($request->only([
                'nombre_institucion',
                'logo_institucional_path',
                'periodo_academico_default_id',
                'tolerancia_asistencia_minutos',
                'requerir_motivo_ausencia',
                'expiracion_contrasena_dias',
                'notificaciones_email_remitente',
            ]));

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => 'El usuario ' . auth()->id() . ' actualizó los parámetros generales del sistema',
                'metodo' => 'POST',
                'ruta' => '/api/configuraciones',
                'direccion_ip' => $request->ip(),
                'navegador' => $request->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $configuracion->fresh(),
                'message' => 'Configuración actualizada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
