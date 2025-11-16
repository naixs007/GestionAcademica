<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\CargaAcademica;
use App\Models\HabilitacionAsistencia;
use App\Services\AsistenciaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Obtener SOLO las habilitaciones activas de hoy
        $habilitacionesHoy = HabilitacionAsistencia::with([
            'cargaAcademica.materia',
            'cargaAcademica.grupo',
            'cargaAcademica.horario',
            'cargaAcademica.aula'
        ])
            ->activas()
            ->hoy()
            ->paraDocente($docente->id)
            ->get();

        // Obtener los IDs de las cargas habilitadas
        $cargasHabilitadasIds = $habilitacionesHoy->pluck('carga_academica_id')->toArray();

        // MODO PRUEBA: Si hay habilitaciones, mostrar todas las cargas habilitadas sin filtrar por día
        // Esto permite probar el sistema cualquier día de la semana
        $cargasHoy = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
            ->where('docente_id', $docente->id)
            ->whereIn('id', $cargasHabilitadasIds)
            ->get();

        // Mapear habilitaciones con sus cargas
        $cargasHoy = $cargasHoy->map(function($carga) use ($habilitacionesHoy) {
            $habilitacion = $habilitacionesHoy->firstWhere('carga_academica_id', $carga->id);
            $carga->habilitacion_id = $habilitacion ? $habilitacion->id : null;
            return $carga;
        });

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
            'habilitacion_id' => 'required|integer|exists:habilitaciones_asistencia,id',
            'password' => 'required|string',
        ]);

        // Verificar contraseña
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta.'
            ], 401);
        }

        // Verificar que la habilitación existe y está activa
        $habilitacion = HabilitacionAsistencia::find($validated['habilitacion_id']);

        if (!$habilitacion || !$habilitacion->estaDisponible()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta habilitación ya no está disponible.'
            ], 403);
        }

        // Verificar que la habilitación pertenece al docente
        if ($habilitacion->docente_id != $docente->id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta habilitación no le pertenece.'
            ], 403);
        }

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

        // Marcar la habilitación como utilizada
        $habilitacion->marcarComoUtilizada();

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
