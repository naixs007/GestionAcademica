<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CargaAcademica;
use App\Services\AsistenciaService;
use Illuminate\Support\Facades\Log;

class AsistenciaController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }

    /**
     * Obtiene los datos necesarios para el marcado de asistencia
     * Calcula la ventana activa de marcado (15 minutos antes y 15 minutos después)
     *
     * @param int $horarioId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMarcadoData($horarioId)
    {
        try {
            $carga = $this->asistenciaService->obtenerCargaAcademica($horarioId);

            if (!$carga) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró carga académica para este horario.'
                ], 404);
            }

            $ventana = $this->asistenciaService->calcularVentana($carga->horario->hora_inicio);

            return response()->json([
                'success' => true,
                'data' => [
                    'carga' => [
                        'id' => $carga->id,
                        'docente' => [
                            'id' => $carga->docente->id,
                            'nombre' => $carga->docente->user->name,
                        ],
                        'materia' => [
                            'id' => $carga->materia->id,
                            'nombre' => $carga->materia->nombre,
                            'codigo' => $carga->materia->codigo,
                        ],
                        'grupo' => [
                            'id' => $carga->grupo->id,
                            'nombre' => $carga->grupo->nombre,
                            'cupo_maximo' => $carga->grupo->cupo_maximo,
                        ],
                        'horario' => [
                            'id' => $carga->horario->id,
                            'dia_semana' => $carga->horario->dia_semana,
                            'hora_inicio' => $carga->horario->hora_inicio,
                            'hora_fin' => $carga->horario->hora_fin,
                        ],
                        'aula' => [
                            'id' => $carga->aula->id,
                            'nombre' => $carga->aula->nombre,
                        ],
                    ],
                    'ventana' => $ventana,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getMarcadoData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registra la asistencia del docente
     * Verifica que la ventana esté activa antes de procesar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarAsistenciaDocente(Request $request)
    {
        try {
            $validated = $request->validate([
                'carga_academica_id' => 'required|integer|exists:carga_academica,id',
                'fecha' => 'required|date',
                'observaciones' => 'nullable|string|max:500',
            ]);

            $carga = $this->asistenciaService->obtenerCargaAcademica($validated['carga_academica_id']);

            if (!$carga) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró carga académica.'
                ], 404);
            }

            $resultado = $this->asistenciaService->registrarAsistenciaDocente(
                $carga,
                $validated['fecha'],
                $validated['observaciones'] ?? null
            );

            if (!$resultado['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['message']
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => $resultado['message'],
                'data' => [
                    'asistencia_id' => $resultado['asistencia']->id,
                    'docente' => $carga->docente->user->name,
                    'estado' => $resultado['asistencia']->estado,
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en registrarAsistenciaDocente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }
}
