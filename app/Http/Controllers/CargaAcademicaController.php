<?php

namespace App\Http\Controllers;

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
        $docentes = Docente::with(['user', 'materias'])->paginate(10);
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
            
            // Obtener la materia y asignarla al docente
            $materia = Materia::findOrFail($validated['materia_id']);
            $materia->update(['docente_id' => $validated['docente_id']]);
            
            // Si se especifica un grupo, asignarlo
            if (!empty($validated['grupo_id'])) {
                $grupo = Grupo::findOrFail($validated['grupo_id']);
                $grupo->update(['materia_id' => $validated['materia_id']]);
            }
            
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
        $docente->load(['user', 'materias.grupos']);
        return view('admin.carga-academica.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        $materias = Materia::all();
        $grupos = Grupo::all();
        $docente->load(['materias']);
        
        return view('admin.carga-academica.edit', compact('docente', 'materias', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $validated = $request->validate([
            'materias' => 'required|array',
            'materias.*' => 'exists:materias,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Actualizar las materias del docente
            foreach ($validated['materias'] as $materiaId) {
                $materia = Materia::findOrFail($materiaId);
                $materia->update(['docente_id' => $docente->id]);
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
    public function destroy($materiaId)
    {
        try {
            $materia = Materia::findOrFail($materiaId);
            $materia->update(['docente_id' => null]);
            
            return redirect()->route('admin.carga-academica.index')
                ->with('success', 'Asignación de materia removida exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al remover asignación: ' . $e->getMessage()]);
        }
    }
}
