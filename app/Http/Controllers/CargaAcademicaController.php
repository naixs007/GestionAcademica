<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademica;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Aula;
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

        $cargas = $query->orderBy('gestion', 'desc')
            ->orderBy('periodo', 'desc')
            ->paginate(15);

        // Obtener datos para filtros
        $gestiones = CargaAcademica::distinct()->pluck('gestion')->sort()->reverse();
        $docentes = Docente::with('user')->paginate(15);

        return view('admin.carga-academica.index', compact('cargas', 'gestiones', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with('user')->get();
        $materias = Materia::all();
        $grupos = Grupo::all();
        $aulas = Aula::all();

        // Agrupar horarios por hora_inicio y hora_fin
        $horariosRaw = Horario::orderBy('hora_inicio')->orderBy('hora_fin')->get();
        $horarios = $horariosRaw->groupBy(function($horario) {
            return $horario->hora_inicio . '-' . $horario->hora_fin;
        })->map(function($grupo) {
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

            // Validar que el docente no tenga otra clase en el mismo horario
            $conflictoDocente = CargaAcademica::where('docente_id', $validated['docente_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoDocente) {
                return back()->withErrors(['error' => 'El docente ya tiene una clase asignada en este horario.'])->withInput();
            }

            // Validar que el aula no esté ocupada en el mismo horario
            $conflictoAula = CargaAcademica::where('aula_id', $validated['aula_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoAula) {
                return back()->withErrors(['error' => 'El aula ya está ocupada en este horario.'])->withInput();
            }

            // Validar que el grupo no tenga otra clase en el mismo horario
            $conflictoGrupo = CargaAcademica::where('grupo_id', $validated['grupo_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoGrupo) {
                return back()->withErrors(['error' => 'El grupo ya tiene una clase asignada en este horario.'])->withInput();
            }

            $cargaAcademica = CargaAcademica::create($validated);

            // Obtener información para el registro
            $docente = Docente::with('user')->find($validated['docente_id']);
            $materia = Materia::find($validated['materia_id']);
            $grupo = Grupo::find($validated['grupo_id']);
            $horario = Horario::find($validated['horario_id']);
            $aula = Aula::find($validated['aula_id']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Asignó %s al docente %s, grupo %s, horario %s, aula %s (Gestión %s-%s)',
                    $materia->nombre,
                    $docente->user->name,
                    $grupo->nombre,
                    $horario->descripcion,
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
        $cargaAcademica->load(['docente.user', 'materia', 'grupo', 'horario', 'aula']);
        return view('admin.carga-academica.show', compact('cargaAcademica'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargaAcademica $cargaAcademica)
    {
        $docentes = Docente::with('user')->get();
        $materias = Materia::all();
        $grupos = Grupo::all();
        $aulas = Aula::all();

        // Agrupar horarios por hora_inicio y hora_fin
        $horariosRaw = Horario::orderBy('hora_inicio')->orderBy('hora_fin')->get();
        $horarios = $horariosRaw->groupBy(function($horario) {
            return $horario->hora_inicio . '-' . $horario->hora_fin;
        })->map(function($grupo) {
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

            // Validar conflictos (excluyendo el registro actual)
            $conflictoDocente = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('docente_id', $validated['docente_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoDocente) {
                return back()->withErrors(['error' => 'El docente ya tiene una clase asignada en este horario.'])->withInput();
            }

            $conflictoAula = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('aula_id', $validated['aula_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoAula) {
                return back()->withErrors(['error' => 'El aula ya está ocupada en este horario.'])->withInput();
            }

            $conflictoGrupo = CargaAcademica::where('id', '!=', $cargaAcademica->id)
                ->where('grupo_id', $validated['grupo_id'])
                ->where('horario_id', $validated['horario_id'])
                ->where('gestion', $validated['gestion'])
                ->where('periodo', $validated['periodo'])
                ->exists();

            if ($conflictoGrupo) {
                return back()->withErrors(['error' => 'El grupo ya tiene una clase asignada en este horario.'])->withInput();
            }

            // Guardar info antigua
            $oldDocente = $cargaAcademica->docente->user->name;
            $oldMateria = $cargaAcademica->materia->nombre;

            $cargaAcademica->update($validated);

            // Obtener info nueva
            $docente = Docente::with('user')->find($validated['docente_id']);
            $materia = Materia::find($validated['materia_id']);
            $grupo = Grupo::find($validated['grupo_id']);
            $horario = Horario::find($validated['horario_id']);
            $aula = Aula::find($validated['aula_id']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Actualizó carga académica de %s - %s a %s al docente %s, grupo %s, horario %s, aula %s (Gestión %s-%s)',
                    $oldDocente,
                    $oldMateria,
                    $materia->nombre,
                    $docente->user->name,
                    $grupo->nombre,
                    $horario->descripcion,
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

            // Guardar información antes de eliminar
            $descripcion = sprintf(
                'Eliminó la asignación de %s del docente %s, grupo %s (Gestión %s-%s)',
                $cargaAcademica->materia->nombre,
                $cargaAcademica->docente->user->name,
                $cargaAcademica->grupo->nombre,
                $cargaAcademica->gestion,
                $cargaAcademica->periodo
            );

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
}
