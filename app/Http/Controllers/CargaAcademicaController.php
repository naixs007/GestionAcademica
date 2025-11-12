<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademica;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
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
            // Verificar si la combinación ya existe
            $exists = CargaAcademica::where('docente_id', $validated['docente_id'])
                ->where('materia_id', $validated['materia_id'])
                ->where('grupo_id', $validated['grupo_id'])
                ->exists();

            if ($exists) {
                return back()->withErrors(['error' => 'Esta asignación ya existe.'])->withInput();
            }

            CargaAcademica::create($validated);

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Carga académica asignada exitosamente.');
        } catch (\Exception $e) {
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
            'asignaciones' => 'required|array',
            'asignaciones.*.materia_id' => 'required|exists:materias,id',
            'asignaciones.*.grupo_id' => 'nullable|exists:grupos,id',
        ]);

        try {
            DB::beginTransaction();

            // Eliminar asignaciones anteriores
            $docente->cargasAcademicas()->delete();

            // Crear nuevas asignaciones
            foreach ($validated['asignaciones'] as $asignacion) {
                CargaAcademica::create([
                    'docente_id' => $docente->id,
                    'materia_id' => $asignacion['materia_id'],
                    'grupo_id' => $asignacion['grupo_id'] ?? null,
                ]);
            }

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
    public function destroy($cargaAcademicaId)
    {
        try {
            $cargaAcademica = CargaAcademica::findOrFail($cargaAcademicaId);
            $cargaAcademica->delete();

            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Asignación de carga académica removida exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al remover asignación: ' . $e->getMessage()]);
        }
    }
}
