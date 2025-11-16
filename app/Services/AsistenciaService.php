<?php

namespace App\Services;

use App\Models\Asistencia;
use App\Models\CargaAcademica;
use Carbon\Carbon;

class AsistenciaService
{
    /**
     * Tiempo de ventana en minutos antes y después de la hora de inicio
     */
    const VENTANA_MINUTOS = 15;

    /**
     * Calcula el estado de la ventana de marcado
     *
     * @param string $horaInicio Hora de inicio de la clase (H:i:s)
     * @return array
     */
    public function calcularVentana(string $horaInicio): array
    {
        $ahora = Carbon::now();
        $horaInicio = Carbon::parse($horaInicio);
        $horaApertura = $horaInicio->copy()->subMinutes(self::VENTANA_MINUTOS);
        $horaCierre = $horaInicio->copy()->addMinutes(self::VENTANA_MINUTOS);

        $esVentanaActiva = $ahora->between($horaApertura, $horaCierre);

        $estadoVentana = 'cerrada';
        if ($esVentanaActiva) {
            $estadoVentana = 'activa';
        } elseif ($ahora->lessThan($horaApertura)) {
            $estadoVentana = 'futura';
        }

        $segundosRestantes = 0;
        if ($esVentanaActiva) {
            $segundosRestantes = max(0, $ahora->diffInSeconds($horaCierre, false));
        }

        return [
            'esVentanaActiva' => $esVentanaActiva,
            'horaApertura' => $horaApertura->format('H:i:s'),
            'horaCierre' => $horaCierre->format('H:i:s'),
            'horaActual' => $ahora->format('H:i:s'),
            'segundosRestantes' => $segundosRestantes,
            'estadoVentana' => $estadoVentana,
        ];
    }

    /**
     * Verifica si existe un registro de asistencia
     *
     * @param int $docenteId
     * @param int $materiaId
     * @param int $grupoId
     * @param string $fecha
     * @return bool
     */
    public function existeAsistencia(int $docenteId, int $materiaId, int $grupoId, string $fecha): bool
    {
        return Asistencia::where('docente_id', $docenteId)
            ->where('materia_id', $materiaId)
            ->where('grupo_id', $grupoId)
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * Crea un registro de asistencia
     *
     * @param array $datos
     * @return Asistencia
     */
    public function crearAsistencia(array $datos): Asistencia
    {
        return Asistencia::create([
            'docente_id' => $datos['docente_id'],
            'materia_id' => $datos['materia_id'],
            'grupo_id' => $datos['grupo_id'],
            'horario_id' => $datos['horario_id'] ?? null,
            'fecha' => $datos['fecha'],
            'estado' => $datos['estado'],
            'hora_llegada' => $datos['hora_llegada'] ?? null,
            'observaciones' => $datos['observaciones'] ?? null,
        ]);
    }

    /**
     * Obtiene la carga académica con relaciones
     *
     * @param int $cargaId
     * @return CargaAcademica|null
     */
    public function obtenerCargaAcademica(int $cargaId): ?CargaAcademica
    {
        return CargaAcademica::with([
            'docente.user',
            'materia',
            'grupo',
            'horario',
            'aula'
        ])->find($cargaId);
    }

    /**
     * Valida y registra asistencia de docente
     *
     * @param CargaAcademica $carga
     * @param string $fecha
     * @param string|null $observaciones
     * @return array ['success' => bool, 'message' => string, 'asistencia' => Asistencia|null]
     */
    public function registrarAsistenciaDocente(CargaAcademica $carga, string $fecha, ?string $observaciones = null): array
    {
        // Verificar ventana activa
        $ventana = $this->calcularVentana($carga->horario->hora_inicio);

        if (!$ventana['esVentanaActiva']) {
            return [
                'success' => false,
                'message' => "La ventana de marcado está cerrada. Debe marcar entre {$ventana['horaApertura']} y {$ventana['horaCierre']}",
                'asistencia' => null,
            ];
        }

        // Verificar duplicados
        if ($this->existeAsistencia($carga->docente_id, $carga->materia_id, $carga->grupo_id, $fecha)) {
            return [
                'success' => false,
                'message' => 'Ya existe un registro de asistencia para esta fecha.',
                'asistencia' => null,
            ];
        }

        // Crear asistencia
        $asistencia = $this->crearAsistencia([
            'docente_id' => $carga->docente_id,
            'materia_id' => $carga->materia_id,
            'grupo_id' => $carga->grupo_id,
            'horario_id' => $carga->horario_id,
            'fecha' => $fecha,
            'estado' => 'Presente',
            'hora_llegada' => Carbon::now()->format('H:i'),
            'observaciones' => $observaciones ?? 'Marcado automáticamente por el docente',
        ]);

        return [
            'success' => true,
            'message' => 'Asistencia registrada exitosamente.',
            'asistencia' => $asistencia,
        ];
    }
}
