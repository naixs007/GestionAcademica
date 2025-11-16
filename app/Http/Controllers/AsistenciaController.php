<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\CargaAcademica;
use App\Services\AsistenciaService;

class AsistenciaController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asistencia::with(['docente.user', 'materia', 'grupo', 'horario']);

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $asistencias = $query->orderBy('fecha', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->withQueryString();

        // Lista de docentes para filtro
        $docentes = Docente::with('user')->orderBy('id')->get();

        return view('admin.asistencia.index', compact('asistencias', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todas las cargas académicas con sus relaciones
        $cargasAcademicas = CargaAcademica::with([
            'docente.user',
            'materia',
            'grupo',
            'horario',
            'aula'
        ])
            ->orderBy('docente_id')
            ->get();

        // Agrupar por docente para facilitar la selección
        $docentesConCargas = $cargasAcademicas->groupBy('docente_id')->map(function($cargas) {
            return [
                'docente' => $cargas->first()->docente,
                'cargas' => $cargas
            ];
        });

        return view('admin.asistencia.create', compact('docentesConCargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'carga_academica_id' => ['required', 'integer', 'exists:carga_academica,id'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:Presente,Ausente,Justificado,Tardanza'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        // Obtener la carga académica
        $carga = $this->asistenciaService->obtenerCargaAcademica($validated['carga_academica_id']);

        if (!$carga) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se encontró la carga académica.');
        }

        // Verificar duplicados
        if ($this->asistenciaService->existeAsistencia(
            $carga->docente_id,
            $carga->materia_id,
            $carga->grupo_id,
            $validated['fecha']
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe un registro de asistencia para este docente, materia, grupo y fecha.');
        }

        // Crear la asistencia
        $this->asistenciaService->crearAsistencia([
            'docente_id' => $carga->docente_id,
            'materia_id' => $carga->materia_id,
            'grupo_id' => $carga->grupo_id,
            'horario_id' => $carga->horario_id,
            'fecha' => $validated['fecha'],
            'estado' => $validated['estado'],
            'hora_llegada' => $validated['hora_llegada'],
            'observaciones' => $validated['observaciones'],
        ]);

        return redirect()->route('admin.asistencia.index')
            ->with('success', 'Asistencia registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asistencia $asistencia)
    {
        $asistencia->load(['docente.user', 'materia', 'grupo', 'horario']);
        return view('admin.asistencia.show', compact('asistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        $asistencia->load(['docente.user', 'materia', 'grupo', 'horario']);
        return view('admin.asistencia.edit', compact('asistencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        $validated = $request->validate([
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:Presente,Ausente,Justificado,Tardanza'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $asistencia->update($validated);

        return redirect()->route('admin.asistencia.index')
            ->with('success', 'Asistencia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();

        return redirect()->route('admin.asistencia.index')
            ->with('success', 'Asistencia eliminada exitosamente.');
    }

    /**
     * Obtener las materias de un docente vía AJAX
     */
    public function getMateriasByDocente($docenteId)
    {
        $cargas = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
            ->where('docente_id', $docenteId)
            ->get();

        return response()->json($cargas);
    }
}
