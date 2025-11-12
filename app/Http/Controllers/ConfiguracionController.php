<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the configuration parameters.
     */
    public function index()
    {
        // Obtener configuraciones desde la tabla settings o cache
        $configuraciones = $this->getConfiguraciones();
        
        return view('admin.configuracion.index', compact('configuraciones'));
    }

    /**
     * Show the form for editing configuration parameters.
     */
    public function edit()
    {
        $configuraciones = $this->getConfiguraciones();
        
        return view('admin.configuracion.edit', compact('configuraciones'));
    }

    /**
     * Update the configuration parameters.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nombre_institucion' => 'required|string|max:255',
            'sigla_institucion' => 'nullable|string|max:50',
            'anio_academico' => 'required|integer|min:2020|max:2100',
            'periodo_academico' => 'required|string|in:1,2,Anual',
            'duracion_periodo' => 'required|integer|min:1|max:12',
            'horas_por_periodo' => 'required|integer|min:30|max:100',
            'dias_laborales' => 'required|array',
            'dias_laborales.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tolerancia_asistencia' => 'required|integer|min:0|max:60',
            'email_notificaciones' => 'nullable|email',
            'permitir_auto_registro' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();
            
            // Guardar cada configuración
            foreach ($validated as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                
                DB::table('configuraciones')->updateOrInsert(
                    ['clave' => $key],
                    [
                        'valor' => $value,
                        'updated_at' => now()
                    ]
                );
                
                // Actualizar cache
                Cache::forget('config_' . $key);
                Cache::put('config_' . $key, $value, now()->addDays(7));
            }
            
            DB::commit();
            
            // Limpiar cache general de configuraciones
            Cache::forget('configuraciones_generales');
            
            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuraciones actualizadas exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar configuraciones: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Get all configuration parameters.
     */
    private function getConfiguraciones()
    {
        return Cache::remember('configuraciones_generales', now()->addDays(7), function () {
            // Verificar si existe la tabla configuraciones
            if (!DB::getSchemaBuilder()->hasTable('configuraciones')) {
                // Retornar valores por defecto
                return $this->getDefaultConfiguraciones();
            }
            
            $configs = DB::table('configuraciones')->pluck('valor', 'clave')->toArray();
            
            // Decodificar JSON si es necesario
            foreach ($configs as $key => $value) {
                if ($this->isJson($value)) {
                    $configs[$key] = json_decode($value, true);
                }
            }
            
            // Mezclar con valores por defecto para claves faltantes
            return array_merge($this->getDefaultConfiguraciones(), $configs);
        });
    }

    /**
     * Get default configuration values.
     */
    private function getDefaultConfiguraciones()
    {
        return [
            'nombre_institucion' => 'Universidad Autónoma Gabriel René Moreno',
            'sigla_institucion' => 'UAGRM',
            'anio_academico' => date('Y'),
            'periodo_academico' => '1',
            'duracion_periodo' => 4,
            'horas_por_periodo' => 48,
            'dias_laborales' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
            'hora_inicio' => '07:00',
            'hora_fin' => '21:00',
            'tolerancia_asistencia' => 15,
            'email_notificaciones' => 'admin@example.com',
            'permitir_auto_registro' => false,
        ];
    }

    /**
     * Check if a string is valid JSON.
     */
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Reset configuration to default values.
     */
    public function reset()
    {
        try {
            DB::beginTransaction();
            
            // Eliminar todas las configuraciones
            DB::table('configuraciones')->truncate();
            
            // Limpiar cache
            Cache::forget('configuraciones_generales');
            
            DB::commit();
            
            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuraciones restablecidas a valores por defecto.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al restablecer configuraciones: ' . $e->getMessage()]);
        }
    }
}
