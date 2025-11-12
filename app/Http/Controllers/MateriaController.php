<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materias = Materia::withCount(['cargasAcademicas', 'grupos'])->paginate(10);
        return view('admin.materias.index', compact('materias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.materias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:materias,codigo',
            'nivel' => 'required|string|max:50',
            'cargaHoraria' => 'required|integer|min:1|max:20',
        ]);

        try {
            Materia::create($validated);

            return redirect()->route('admin.materia.index')
                ->with('success', 'Materia registrada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar materia: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        $materia->load(['cargasAcademicas.docente.user', 'cargasAcademicas.grupo', 'horarios', 'aulas']);
        return view('admin.materias.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        return view('admin.materias.edit', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:materias,codigo,' . $materia->id,
            'nivel' => 'required|string|max:50',
            'cargaHoraria' => 'required|integer|min:1|max:20',
        ]);

        try {
            $materia->update($validated);

            return redirect()->route('admin.materia.index')
                ->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar materia: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materia $materia)
    {
        try {
            // Verificar si tiene asignaciones de carga académica
            if ($materia->cargasAcademicas()->count() > 0) {
                return back()->withErrors(['error' => 'No se puede eliminar la materia porque tiene asignaciones de carga académica.']);
            }

            $materia->delete();

            return redirect()->route('admin.materia.index')
                ->with('success', 'Materia eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar materia: ' . $e->getMessage()]);
        }
    }
}
