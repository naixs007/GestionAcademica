<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademica;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Aula;
use App\Models\Asistencia;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargaAcademicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CargaAcademica::with(['docente.user', 'materia', 'grupo', 'horario', 'aula']);

        // Filtros opcionales
        if ($request->filled('gestion')) {
            $query->where('gestion', $request->gestion);
        }
        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }
        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }
        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        $cargas = $query->orderBy('gestion', 'desc')
            ->orderBy('periodo', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Obtener datos para filtros
        $gestiones = CargaAcademica::distinct()->pluck('gestion')->sort()->reverse();
        $docentes = Docente::with('user')->get();
        $materias = Materia::all();
        $grupos = Grupo::all();

        return view('admin.carga-academica.index', compact('cargas', 'gestiones', 'docentes', 'materias', 'grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with(['user', 'cargasAcademicas.materia'])->get();
        $materias = Materia::all();
        $grupos = Grupo::all();
        $aulas = Aula::all();

        // Agrupar horarios por hora_inicio y hora_fin
        $horariosRaw = Horario::orderBy('hora_inicio')->orderBy('hora_fin')->get();
        $horarios = $horariosRaw->groupBy(function ($horario) {
            return $horario->hora_inicio . '-' . $horario->hora_fin;
        })->map(function ($grupo) {
            $horarioBase = $grupo->first();
            $horarioBase->dias_agrupados = $grupo->pluck('dia_semana')->toArray();
            $horarioBase->ids = $grupo->pluck('id')->toArray();
            return $horarioBase;
        })->values();

        return view('admin.carga-academica.create', compact('docentes', 'materias', 'grupos', 'horarios', 'aulas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'materia_id' => 'required|exists:materias,id',
            'grupo_id' => 'required|exists:grupos,id',
            'horario_id' => 'required|exists:horarios,id',
            'aula_id' => 'required|exists:aulas,id',
            'gestion' => 'required|integer|min:2020|max:2099',
            'periodo' => 'required|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            // [VALIDACIÓN CRÍTICA 1] Duplicidad de Curso
            // Una materia solo puede pertenecer a un grupo (puede repetirse en diferentes grupos)
            $cursoDuplicado = CargaAcademica::where('materia_id', $validated['materia_id'])
                ->where('grupo_id', $validated['grupo_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($cursoDuplicado) {
                $materia = Materia::find($validated['materia_id']);
                $grupo = Grupo::find($validated['grupo_id']);
                return back()->withErrors([
                    'error' => sprintf(
                        'La materia "%s" ya está asignada al grupo "%s" en la gestión %s-%s. Una materia solo puede asignarse una vez por grupo, pero puede asignarse a diferentes grupos.',
                        $materia->nombre,
                        $grupo->nombre,
                        $validated['gestion'],
                        $validated['periodo']
                    )
                ])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 2] Conflicto de Horario del Docente
            $conflictoDocente = CargaAcademica::where('docente_id', $validated['docente_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoDocente) {
                return back()->withErrors(['error' => 'El docente ya tiene una clase asignada en este horario para la gestión y periodo seleccionados.'])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 3] Conflicto de Uso del Aula
            $conflictoAula = CargaAcademica::where('aula_id', $validated['aula_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoAula) {
                return back()->withErrors(['error' => 'El aula ya está ocupada en este horario para la gestión y periodo seleccionados.'])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 4] Conflicto de Horario del Grupo
            $conflictoGrupo = CargaAcademica::where('grupo_id', $validated['grupo_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoGrupo) {
                return back()->withErrors([
                    'horario_id' => 'El grupo ya tiene otra materia asignada en este bloque horario.'
                ])->withInput();
            }

            // [VALIDACIÓN BUSINESS 1] Límite de Carga Máxima del Docente
            $docente = Docente::with(['cargasAcademicas.materia'])->find($validated['docente_id']);
            $materia = Materia::find($validated['materia_id']);

            // Calcular carga actual del docente en este periodo
            $cargaActual = $docente->cargasAcademicas
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->sum(function ($carga) {
                    return $carga->materia ? $carga->materia->cargaHoraria : 0;
                });

            $cargaMaxima = $docente->carga_maxima_horas ?? 24;
            $cargaDespuesDeAsignar = $cargaActual + $materia->cargaHoraria;

            if ($cargaDespuesDeAsignar > $cargaMaxima) {
                return back()->withErrors([
                    'error' => sprintf(
                        'El docente excedería su carga horaria máxima. Actual: %.2f hrs, Materia: %.2f hrs, Total: %.2f hrs, Máximo permitido: %.2f hrs',
                        $cargaActual,
                        $materia->cargaHoraria,
                        $cargaDespuesDeAsignar,
                        $cargaMaxima
                    )
                ])->withInput();
            }

            // [VALIDACIÓN BUSINESS 2] Capacidad del Aula
            $grupo = Grupo::find($validated['grupo_id']);
            $aula = Aula::find($validated['aula_id']);

            if ($grupo->cupo_maximo > $aula->capacidad) {
                return back()->withErrors([
                    'error' => sprintf(
                        'El cupo del grupo (%d estudiantes) excede la capacidad del aula (%d personas).',
                        $grupo->cupo_maximo,
                        $aula->capacidad
                    )
                ])->withInput();
            }

            // Crear la carga académica
            $cargaAcademica = CargaAcademica::create($validated);

            // Obtener información para el registro
            $horario = Horario::find($validated['horario_id']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Asignó %s al docente %s, grupo %s, horario %s (%s-%s), aula %s (Gestión %s-%s)',
                    $materia->nombre,
                    $docente->user->name,
                    $grupo->nombre,
                    $horario->dia_semana,
                    substr($horario->hora_inicio, 0, 5),
                    substr($horario->hora_fin, 0, 5),
                    $aula->codigo,
                    $validated['gestion'],
                    $validated['periodo']
                ),
                'metodo' => 'POST',
                'ruta' => request()->path(),
                'direccion_ip' => $request->ip(),
                'navegador' => $request->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Carga académica asignada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al asignar carga académica: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CargaAcademica $cargaAcademica)
    {
        $cargaAcademica->load(['docente.user', 'docente.cargasAcademicas.materia', 'materia', 'grupo', 'horario', 'aula']);
        return view('admin.carga-academica.show', compact('cargaAcademica'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargaAcademica $cargaAcademica)
    {
        $docentes = Docente::with(['user', 'cargasAcademicas.materia'])->get();
        $materias = Materia::all();
        $grupos = Grupo::all();
        $aulas = Aula::all();

        // Agrupar horarios por hora_inicio y hora_fin
        $horariosRaw = Horario::orderBy('hora_inicio')->orderBy('hora_fin')->get();
        $horarios = $horariosRaw->groupBy(function ($horario) {
            return $horario->hora_inicio . '-' . $horario->hora_fin;
        })->map(function ($grupo) {
            $horarioBase = $grupo->first();
            $horarioBase->dias_agrupados = $grupo->pluck('dia_semana')->toArray();
            $horarioBase->ids = $grupo->pluck('id')->toArray();
            return $horarioBase;
        })->values();

        return view('admin.carga-academica.edit', compact('cargaAcademica', 'docentes', 'materias', 'grupos', 'horarios', 'aulas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CargaAcademica $cargaAcademica)
    {
        $validated = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'materia_id' => 'required|exists:materias,id',
            'grupo_id' => 'required|exists:grupos,id',
            'horario_id' => 'required|exists:horarios,id',
            'aula_id' => 'required|exists:aulas,id',
            'gestion' => 'required|integer|min:2020|max:2099',
            'periodo' => 'required|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            // [RESTRICCIÓN CRÍTICA] Inmutabilidad si tiene registros de Asistencia
            $tieneAsistencias = $cargaAcademica->asistencias()->exists();

            if ($tieneAsistencias) {
                // Verificar si se intentan cambiar campos críticos
                $camposCriticosModificados =
                    $cargaAcademica->docente_id != $validated['docente_id'] ||
                    $cargaAcademica->materia_id != $validated['materia_id'] ||
                    $cargaAcademica->grupo_id != $validated['grupo_id'];

                if ($camposCriticosModificados) {
                    return back()->withErrors([
                        'error' => 'No se pueden modificar el docente, materia o grupo porque ya existen asistencias registradas para esta carga académica. Solo puede modificar el horario, aula, gestión o periodo.'
                    ])->withInput();
                }
            }

            // [VALIDACIÓN CRÍTICA 1] Duplicidad de Curso (excluyendo el registro actual)
            // Una materia solo puede pertenecer a un grupo (puede repetirse en diferentes grupos)
            $cursoDuplicado = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('materia_id', $validated['materia_id'])
                ->where('grupo_id', $validated['grupo_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($cursoDuplicado) {
                $materia = Materia::find($validated['materia_id']);
                $grupo = Grupo::find($validated['grupo_id']);
                return back()->withErrors([
                    'error' => sprintf(
                        'La materia "%s" ya está asignada al grupo "%s" en la gestión %s-%s. Una materia solo puede asignarse una vez por grupo, pero puede asignarse a diferentes grupos.',
                        $materia->nombre,
                        $grupo->nombre,
                        $validated['gestion'],
                        $validated['periodo']
                    )
                ])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 2] Conflicto NETO de Horario del Docente
            $conflictoDocente = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('docente_id', $validated['docente_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoDocente) {
                return back()->withErrors(['error' => 'El docente ya tiene una clase asignada en este horario para la gestión y periodo seleccionados.'])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 3] Conflicto NETO de Uso del Aula
            $conflictoAula = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('aula_id', $validated['aula_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoAula) {
                return back()->withErrors(['error' => 'El aula ya está ocupada en este horario para la gestión y periodo seleccionados.'])->withInput();
            }

            // [VALIDACIÓN CRÍTICA 4] Conflicto NETO de Horario del Grupo
            $conflictoGrupo = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('grupo_id', $validated['grupo_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoGrupo) {
                return back()->withErrors(['error' => 'El grupo ya tiene una clase asignada en este horario para la gestión y periodo seleccionados.'])->withInput();
            }

            // [VALIDACIÓN BUSINESS 1] Revalidar Límite de Carga Máxima del Docente
            $docente = Docente::with(['cargasAcademicas.materia'])->find($validated['docente_id']);
            $materia = Materia::find($validated['materia_id']);

            // Calcular carga actual excluyendo esta asignación (para no contarla dos veces)
            $cargaActual = $docente->cargasAcademicas
                ->where('id', '!=', $cargaAcademica->id)
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->sum(function ($carga) {
                    return $carga->materia ? $carga->materia->cargaHoraria : 0;
                });

            $cargaMaxima = $docente->carga_maxima_horas ?? 24;
            $cargaDespuesDeAsignar = $cargaActual + $materia->cargaHoraria;

            if ($cargaDespuesDeAsignar > $cargaMaxima) {
                return back()->withErrors([
                    'error' => sprintf(
                        'El docente excedería su carga horaria máxima. Actual: %.2f hrs, Materia: %.2f hrs, Total: %.2f hrs, Máximo permitido: %.2f hrs',
                        $cargaActual,
                        $materia->cargaHoraria,
                        $cargaDespuesDeAsignar,
                        $cargaMaxima
                    )
                ])->withInput();
            }

            // [VALIDACIÓN BUSINESS 2] Capacidad del Aula
            $grupo = Grupo::find($validated['grupo_id']);
            $aula = Aula::find($validated['aula_id']);

            if ($grupo->cupo_maximo > $aula->capacidad) {
                return back()->withErrors([
                    'error' => sprintf(
                        'El cupo del grupo (%d estudiantes) excede la capacidad del aula (%d personas).',
                        $grupo->cupo_maximo,
                        $aula->capacidad
                    )
                ])->withInput();
            }

            // Guardar info antigua para bitácora
            $oldDocente = $cargaAcademica->docente->user->name;
            $oldMateria = $cargaAcademica->materia->nombre;

            // Actualizar la carga académica
            $cargaAcademica->update($validated);

            // Obtener info nueva
            $horario = Horario::find($validated['horario_id']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Actualizó carga académica de %s - %s a %s al docente %s, grupo %s, horario %s (%s-%s), aula %s (Gestión %s-%s)',
                    $oldDocente,
                    $oldMateria,
                    $materia->nombre,
                    $docente->user->name,
                    $grupo->nombre,
                    $horario->dia_semana,
                    substr($horario->hora_inicio, 0, 5),
                    substr($horario->hora_fin, 0, 5),
                    $aula->codigo,
                    $validated['gestion'],
                    $validated['periodo']
                ),
                'metodo' => 'PUT',
                'ruta' => request()->path(),
                'direccion_ip' => $request->ip(),
                'navegador' => $request->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Carga académica actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar carga académica: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CargaAcademica $cargaAcademica)
    {
        try {
            DB::beginTransaction();

            // [RESTRICCIÓN CRÍTICA] Prohibir eliminación si existen registros de Asistencia
            // Verificar usando los campos individuales ya que no hay FK directa
            $tieneAsistencias = Asistencia::where('docente_id', $cargaAcademica->docente_id)
                ->where('materia_id', $cargaAcademica->materia_id)
                ->where('grupo_id', $cargaAcademica->grupo_id)
                ->exists();

            if ($tieneAsistencias) {
                $cantidadAsistencias = Asistencia::where('docente_id', $cargaAcademica->docente_id)
                    ->where('materia_id', $cargaAcademica->materia_id)
                    ->where('grupo_id', $cargaAcademica->grupo_id)
                    ->count();
                return back()->withErrors([
                    'error' => sprintf(
                        'No se puede eliminar esta carga académica porque tiene %d registro(s) de asistencia vinculados. Debe eliminar primero las asistencias o mantener el registro por trazabilidad histórica.',
                        $cantidadAsistencias
                    )
                ]);
            }            // Guardar información antes de eliminar
            $descripcion = sprintf(
                'Eliminó la asignación de %s del docente %s, grupo %s (Gestión %s-%s)',
                $cargaAcademica->materia->nombre,
                $cargaAcademica->docente->user->name,
                $cargaAcademica->grupo->nombre,
                $cargaAcademica->gestion,
                $cargaAcademica->periodo
            );

            // Eliminar el registro (las FK con ON DELETE CASCADE eliminarán datos relacionados automáticamente)
            $cargaAcademica->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => $descripcion,
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Asignación de carga académica eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Verificar conflictos de horario (AJAX)
     */
    public function verificarConflictos(Request $request)
    {
        $conflictos = [];

        // Validar que lleguen los datos necesarios
        if (!$request->filled(['horario_id', 'gestion', 'periodo'])) {
            return response()->json(['conflictos' => []]);
        }

        $horarioId = $request->horario_id;
        $gestion = $request->gestion;
        $periodo = $request->periodo;
        $excludeId = $request->exclude_id; // Para excluir el registro actual en edición

        // Verificar conflicto de docente
        if ($request->filled('docente_id')) {
            $query = CargaAcademica::with(['materia', 'grupo', 'aula'])
                ->where('docente_id', $request->docente_id)
                ->where('horario_id', $horarioId)
                ->where('gestion', $gestion)
                ->where('periodo', $periodo);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $conflictoDocente = $query->first();

            if ($conflictoDocente) {
                $conflictos[] = [
                    'tipo' => 'docente',
                    'mensaje' => 'El docente ya tiene asignada la materia "' . $conflictoDocente->materia->nombre .
                        '" con el grupo "' . $conflictoDocente->grupo->codigo .
                        '" en el aula "' . $conflictoDocente->aula->codigo .
                        '" en este horario.'
                ];
            }
        }

        // Verificar conflicto de aula
        if ($request->filled('aula_id')) {
            $query = CargaAcademica::with(['docente.user', 'materia', 'grupo'])
                ->where('aula_id', $request->aula_id)
                ->where('horario_id', $horarioId)
                ->where('gestion', $gestion)
                ->where('periodo', $periodo);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $conflictoAula = $query->first();

            if ($conflictoAula) {
                $conflictos[] = [
                    'tipo' => 'aula',
                    'mensaje' => 'El aula ya está ocupada por el docente "' . $conflictoAula->docente->user->name .
                        '" impartiendo "' . $conflictoAula->materia->nombre .
                        '" al grupo "' . $conflictoAula->grupo->codigo .
                        '" en este horario.'
                ];
            }
        }

        // Verificar conflicto de grupo
        if ($request->filled('grupo_id')) {
            $query = CargaAcademica::with(['docente.user', 'materia', 'aula'])
                ->where('grupo_id', $request->grupo_id)
                ->where('horario_id', $horarioId)
                ->where('gestion', $gestion)
                ->where('periodo', $periodo);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $conflictoGrupo = $query->first();

            if ($conflictoGrupo) {
                $conflictos[] = [
                    'tipo' => 'grupo',
                    'mensaje' => 'El grupo ya tiene asignada la materia "' . $conflictoGrupo->materia->nombre .
                        '" con el docente "' . $conflictoGrupo->docente->user->name .
                        '" en el aula "' . $conflictoGrupo->aula->codigo .
                        '" en este horario.'
                ];
            }
        }

        return response()->json(['conflictos' => $conflictos]);
    }

    /**
     * Mostrar toda la carga académica de un docente específico
     */
    public function verDocente($docenteId)
    {
        $docente = Docente::with(['user', 'cargasAcademicas.materia', 'cargasAcademicas.grupo', 'cargasAcademicas.horario', 'cargasAcademicas.aula'])
            ->findOrFail($docenteId);

        // Agrupar cargas por gestión y periodo
        $cargasPorPeriodo = $docente->cargasAcademicas->groupBy(function($carga) {
            return $carga->gestion . '-' . $carga->periodo;
        });

        // Calcular estadísticas
        $estadisticas = [];
        foreach ($cargasPorPeriodo as $periodo => $cargas) {
            $totalHoras = $cargas->sum(function($carga) {
                return $carga->materia ? $carga->materia->cargaHoraria : 0;
            });

            [$gestion, $periodoNum] = explode('-', $periodo);
            $estadisticas[$periodo] = [
                'gestion' => $gestion,
                'periodo' => $periodoNum,
                'total_materias' => $cargas->count(),
                'total_horas' => $totalHoras,
                'porcentaje' => ($totalHoras / ($docente->carga_maxima_horas ?? 24)) * 100,
                'cargas' => $cargas
            ];
        }

        // Ordenar por gestión y periodo descendente
        $estadisticas = collect($estadisticas)->sortByDesc(function($item) {
            return $item['gestion'] . $item['periodo'];
        });

        return view('admin.carga-academica.docente', compact('docente', 'estadisticas'));
    }
}
