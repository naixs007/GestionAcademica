<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\CargaAcademica;
use App\Services\AsistenciaService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaDocenteController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }
    /**
     * Mostrar las asistencias del docente autenticado
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Obtener el docente asociado al usuario
        $docente = Docente::where('user_id', $user->id)->first();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('error', 'No se encontró información del docente.');
        }

        // Obtener asistencias del docente con filtros
        $query = Asistencia::with(['materia', 'grupo', 'horario'])
            ->where('docente_id', $docente->id)
            ->orderByDesc('fecha')
            ->orderByDesc('created_at');

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $asistencias = $query->paginate(15)->withQueryString();

        return view('docente.asistencia.index', compact('asistencias', 'docente'));
    }

    /**
     * Mostrar vista para marcar asistencia
     */
    public function marcar()
    {
        $user = Auth::user();

        // Obtener el docente asociado al usuario
        $docente = Docente::where('user_id', $user->id)->first();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('error', 'No se encontró información del docente.');
        }

        // Obtener cargas académicas del docente para hoy
        $diaHoy = Carbon::now()->locale('es')->dayName;

        $cargasHoy = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
            ->where('docente_id', $docente->id)
            ->whereHas('horario', function($query) use ($diaHoy) {
                $query->where('dia_semana', 'LIKE', '%' . $diaHoy . '%');
            })
            ->get();

        return view('docente.asistencia.marcar', compact('docente', 'cargasHoy'));
    }

    /**
     * Procesar el marcado de asistencia
     */
    public function procesarMarcado(Request $request)
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();

        if (!$docente) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró información del docente.'
            ], 404);
        }

        $validated = $request->validate([
            'carga_academica_id' => 'required|integer|exists:carga_academica,id',
        ]);

        // Obtener la carga académica y verificar que pertenece al docente
        $carga = CargaAcademica::with(['horario', 'materia', 'grupo'])
            ->where('id', $validated['carga_academica_id'])
            ->where('docente_id', $docente->id)
            ->first();

        if (!$carga) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes asignada esta carga académica.'
            ], 403);
        }

        // Usar el servicio para registrar la asistencia
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $resultado = $this->asistenciaService->registrarAsistenciaDocente(
            $carga,
            $fechaHoy
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
                'hora_llegada' => $resultado['asistencia']->hora_llegada,
            ]
        ], 201);
    }

    /**
     * Mostrar carga académica del docente
     */
    public function cargaAcademica()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('error', 'No se encontró información del docente.');
        }

        // Obtener cargas académicas del docente
        $cargas = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
            ->where('docente_id', $docente->id)
            ->orderBy('horario_id')
            ->get()
            ->groupBy('horario.dia_semana');

        return view('docente.carga-academica', compact('docente', 'cargas'));
    }
}
