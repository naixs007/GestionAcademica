<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = Grupo::with('materias')->paginate(10);
        return view('admin.grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materias = Materia::all();
        return view('admin.grupos.create', compact('materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'materia_id' => 'required|exists:materias,id',
            'capacidad' => 'required|integer|min:1|max:100',
        ]);

        try {
            Grupo::create($validated);
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar grupo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        $grupo->load(['materias.docente.user']);
        return view('admin.grupos.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        $materias = Materia::all();
        return view('admin.grupos.edit', compact('grupo', 'materias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'materia_id' => 'required|exists:materias,id',
            'capacidad' => 'required|integer|min:1|max:100',
        ]);

        try {
            $grupo->update($validated);
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar grupo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        try {
            $grupo->delete();
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar grupo: ' . $e->getMessage()]);
        }
    }
}
