<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
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
            'nivel' => 'required|integer|min:1|max:10',
            'cargaHoraria' => 'required|integer|min:1|max:20',
        ]);

        try {
            DB::beginTransaction();

            $materia = Materia::create($validated);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Creó la materia '{$materia->nombre}' con código '{$materia->codigo}' (Nivel: {$materia->nivel}, Carga horaria: {$materia->cargaHoraria}h)",
                'metodo' => 'POST',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.materias.index')
                ->with('success', 'Materia registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            'nivel' => 'required|integer|min:1|max:10',
            'cargaHoraria' => 'required|integer|min:1|max:20',
        ]);

        try {
            DB::beginTransaction();

            $oldNombre = $materia->nombre;
            $materia->update($validated);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Actualizó la materia '{$oldNombre}' a '{$materia->nombre}' (Código: {$materia->codigo}, Nivel: {$materia->nivel}, Carga horaria: {$materia->cargaHoraria}h)",
                'metodo' => 'PUT',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.materias.index')
                ->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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

            DB::beginTransaction();

            // Capturar datos antes de eliminar
            $nombreMateria = $materia->nombre;
            $codigoMateria = $materia->codigo;

            $materia->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Eliminó la materia '{$nombreMateria}' con código '{$codigoMateria}'",
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.materias.index')
                ->with('success', 'Materia eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar materia: ' . $e->getMessage()]);
        }
    }
}
