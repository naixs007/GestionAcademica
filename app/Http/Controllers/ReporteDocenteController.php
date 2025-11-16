<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Asistencia;
use App\Models\CargaAcademica;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteDocenteController extends Controller
{
    /**
     * Mostrar formulario de reportes personalizables
     */
    public function index(Request $request)
    {
        $docentes = Docente::with('user')->orderBy('id')->get();

        // Si hay filtros aplicados, obtener datos
        $reporte = null;
        if ($request->filled('docente_id')) {
            $reporte = $this->generarReporte($request);
        }

        return view('admin.reportes.docentes.index', compact('docentes', 'reporte'));
    }

    /**
     * Generar reporte con los filtros seleccionados
     */
    private function generarReporte(Request $request)
    {
        $docenteId = $request->input('docente_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $incluir = $request->input('incluir', []);

        $docente = Docente::with(['user', 'cargasAcademicas.materia', 'cargasAcademicas.grupo'])
            ->findOrFail($docenteId);

        $data = [
            'docente' => $docente,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'incluir' => $incluir,
            'generado_en' => now(),
        ];

        // Información básica (siempre incluida)
        $data['info_basica'] = [
            'nombre' => $docente->user->name,
            'email' => $docente->user->email,
            'especialidad' => $docente->especialidad,
            'carga_maxima' => $docente->carga_maxima,
            'grado_academico' => $docente->grado_academico,
            'telefono' => $docente->telefono,
        ];

        // Cargas académicas
        if (in_array('cargas', $incluir)) {
            $cargas = $docente->cargasAcademicas()
                ->with(['materia', 'grupo', 'horario', 'aula'])
                ->get();

            $data['cargas'] = $cargas;
            $data['total_horas'] = $cargas->sum(function($carga) {
                return $carga->materia->carga_horaria ?? 0;
            });
        }

        // Asistencias
        if (in_array('asistencias', $incluir)) {
            $queryAsistencias = Asistencia::where('docente_id', $docenteId);

            if ($fechaInicio) {
                $queryAsistencias->where('fecha', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $queryAsistencias->where('fecha', '<=', $fechaFin);
            }

            $asistencias = $queryAsistencias
                ->with(['materia', 'grupo', 'horario'])
                ->orderBy('fecha', 'desc')
                ->get();

            $data['asistencias'] = $asistencias;

            // Estadísticas de asistencias
            $data['estadisticas_asistencias'] = [
                'total' => $asistencias->count(),
                'presentes' => $asistencias->where('estado', 'Presente')->count(),
                'ausentes' => $asistencias->where('estado', 'Ausente')->count(),
                'tardanzas' => $asistencias->where('estado', 'Tardanza')->count(),
                'justificados' => $asistencias->where('estado', 'Justificado')->count(),
                'porcentaje_asistencia' => $asistencias->count() > 0
                    ? round(($asistencias->whereIn('estado', ['Presente', 'Tardanza'])->count() / $asistencias->count()) * 100, 2)
                    : 0,
            ];
        }

        // Historial de habilitaciones
        if (in_array('habilitaciones', $incluir)) {
            $habilitaciones = $docente->habilitacionesAsistencia()
                ->with(['cargaAcademica.materia', 'cargaAcademica.grupo'])
                ->orderBy('fecha', 'desc')
                ->get();

            $data['habilitaciones'] = $habilitaciones;
            $data['estadisticas_habilitaciones'] = [
                'total' => $habilitaciones->count(),
                'utilizadas' => $habilitaciones->where('estado', 'Utilizada')->count(),
                'canceladas' => $habilitaciones->where('estado', 'Cancelada')->count(),
                'habilitadas' => $habilitaciones->where('estado', 'Habilitada')->count(),
            ];
        }

        return $data;
    }

    /**
     * Descargar reporte en PDF
     */
    public function descargarPDF(Request $request)
    {
        $reporte = $this->generarReporte($request);

        $pdf = Pdf::loadView('admin.reportes.docentes.pdf', $reporte)
            ->setPaper('letter', 'portrait')
            ->setOption('defaultFont', 'Arial');

        $nombreDocente = str_replace(' ', '_', $reporte['docente']->user->name);
        $fecha = now()->format('Y-m-d');
        $nombreArchivo = "Reporte_Docente_{$nombreDocente}_{$fecha}.pdf";

        return $pdf->download($nombreArchivo);
    }

    /**
     * Comparar múltiples docentes
     */
    public function comparar(Request $request)
    {
        $docentesIds = $request->input('docentes', []);
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        if (count($docentesIds) < 2) {
            return redirect()->back()->with('error', 'Seleccione al menos 2 docentes para comparar.');
        }

        $docentes = Docente::with(['user', 'cargasAcademicas'])
            ->whereIn('id', $docentesIds)
            ->get();

        $comparacion = [];
        foreach ($docentes as $docente) {
            $queryAsistencias = Asistencia::where('docente_id', $docente->id);

            if ($fechaInicio) {
                $queryAsistencias->where('fecha', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $queryAsistencias->where('fecha', '<=', $fechaFin);
            }

            $asistencias = $queryAsistencias->get();

            $comparacion[] = [
                'docente' => $docente,
                'total_cargas' => $docente->cargasAcademicas->count(),
                'total_horas' => $docente->cargasAcademicas->sum(function($carga) {
                    return $carga->materia->carga_horaria ?? 0;
                }),
                'total_asistencias' => $asistencias->count(),
                'presentes' => $asistencias->where('estado', 'Presente')->count(),
                'tardanzas' => $asistencias->where('estado', 'Tardanza')->count(),
                'ausentes' => $asistencias->where('estado', 'Ausente')->count(),
                'porcentaje_asistencia' => $asistencias->count() > 0
                    ? round(($asistencias->whereIn('estado', ['Presente', 'Tardanza'])->count() / $asistencias->count()) * 100, 2)
                    : 0,
            ];
        }

        return view('admin.reportes.docentes.comparacion', compact('comparacion', 'fechaInicio', 'fechaFin'));
    }
}
