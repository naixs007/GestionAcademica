<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademica;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargaAcademicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docentes = Docente::with(['user', 'cargasAcademicas.materia', 'cargasAcademicas.grupo'])->paginate(10);
        return view('admin.carga-academica.index', compact('docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with('user')->get();
        $materias = Materia::all();
        $grupos = Grupo::all();

        return view('admin.carga-academica.create', compact('docentes', 'materias', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'materia_id' => 'required|exists:materias,id',
            'grupo_id' => 'nullable|exists:grupos,id',
        ]);

        try {
            DB::beginTransaction();

            // Verificar si la combinación ya existe
            $exists = CargaAcademica::where('docente_id', $validated['docente_id'])
                ->where('materia_id', $validated['materia_id'])
                ->where('grupo_id', $validated['grupo_id'])
                ->exists();

            if ($exists) {
                return back()->withErrors(['error' => 'Esta asignación ya existe.'])->withInput();
            }

            $cargaAcademica = CargaAcademica::create($validated);

            // Obtener información para el registro
            $docente = Docente::with('user')->find($validated['docente_id']);
            $materia = Materia::find($validated['materia_id']);
            $grupo = $validated['grupo_id'] ? Grupo::find($validated['grupo_id']) : null;

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Asignó la materia "%s" al docente "%s"%s',
                    $materia->nombre,
                    $docente->user->name,
                    $grupo ? ' en el grupo "' . $grupo->nombre . '"' : ''
                ),
                'metodo' => 'POST',
                'ruta' => route('admin.carga-academica.store'),
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
    public function show(Docente $docente)
    {
        $docente->load(['user', 'cargasAcademicas.materia', 'cargasAcademicas.grupo']);
        return view('admin.carga-academica.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        $materias = Materia::all();
        $grupos = Grupo::all();
        $docente->load(['cargasAcademicas.materia', 'cargasAcademicas.grupo']);

        return view('admin.carga-academica.edit', compact('docente', 'materias', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $validated = $request->validate([
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
        ], [
            'materias.required' => 'Debe seleccionar al menos una materia.',
            'materias.min' => 'Debe seleccionar al menos una materia.',
        ]);

        try {
            DB::beginTransaction();

            // Eliminar asignaciones anteriores
            $docente->cargasAcademicas()->delete();

            // Crear nuevas asignaciones
            $materiasNombres = [];
            foreach ($validated['materias'] as $materiaId) {
                CargaAcademica::create([
                    'docente_id' => $docente->id,
                    'materia_id' => $materiaId,
                    'grupo_id' => null, // Sin grupo específico por ahora
                ]);

                $materia = Materia::find($materiaId);
                $materiasNombres[] = $materia->nombre;
            }

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => sprintf(
                    'Actualizó la carga académica del docente "%s". Materias asignadas: %s',
                    $docente->user->name,
                    implode(', ', $materiasNombres)
                ),
                'metodo' => 'PUT',
                'ruta' => route('admin.carga-academica.update', $docente->id),
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
    public function destroy(Request $request, $cargaAcademicaId)
    {
        try {
            DB::beginTransaction();

            $cargaAcademica = CargaAcademica::with(['docente.user', 'materia', 'grupo'])->findOrFail($cargaAcademicaId);

            // Guardar información antes de eliminar
            $descripcion = sprintf(
                'Eliminó la asignación de la materia "%s" del docente "%s"%s',
                $cargaAcademica->materia->nombre,
                $cargaAcademica->docente->user->name,
                $cargaAcademica->grupo ? ' del grupo "' . $cargaAcademica->grupo->nombre . '"' : ''
            );

            $cargaAcademica->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => $descripcion,
                'metodo' => 'DELETE',
                'ruta' => route('admin.carga-academica.destroy', $cargaAcademicaId),
                'direccion_ip' => $request->ip(),
                'navegador' => $request->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Asignación de carga académica removida exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al remover asignación: ' . $e->getMessage()]);
        }
    }
}
