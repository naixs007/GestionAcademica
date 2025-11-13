<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aulas = Aula::withCount('materias')->paginate(10);
        return view('admin.aulas.index', compact('aulas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.aulas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:aulas,nombre',
            'capacidad' => 'required|integer|min:1|max:200',
            'tipo' => 'required|in:aula,laboratorio',
        ], [
            'nombre.unique' => 'Ya existe un aula con este nombre.',
            'tipo.in' => 'El tipo debe ser aula o laboratorio.',
        ]);

        try {
            DB::beginTransaction();

            $aula = Aula::create($validated);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Creó el {$aula->tipo} '{$aula->nombre}' con capacidad de {$aula->capacidad} personas",
                'metodo' => 'POST',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.aula.index')
                ->with('success', ucfirst($aula->tipo) . ' registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar aula: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aula $aula)
    {
        $aula->load('materias');
        return view('admin.aulas.show', compact('aula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aula $aula)
    {
        return view('admin.aulas.edit', compact('aula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aula $aula)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:aulas,nombre,' . $aula->id,
            'capacidad' => 'required|integer|min:1|max:200',
            'tipo' => 'required|in:aula,laboratorio',
        ], [
            'nombre.unique' => 'Ya existe un aula con este nombre.',
            'tipo.in' => 'El tipo debe ser aula o laboratorio.',
        ]);

        try {
            DB::beginTransaction();

            $oldNombre = $aula->nombre;
            $oldTipo = $aula->tipo;
            $aula->update($validated);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Actualizó el {$oldTipo} '{$oldNombre}' a '{$aula->nombre}' (Tipo: {$aula->tipo}, Capacidad: {$aula->capacidad} personas)",
                'metodo' => 'PUT',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.aula.index')
                ->with('success', ucfirst($aula->tipo) . ' actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar aula: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aula $aula)
    {
        try {
            // Verificar si tiene materias asignadas
            if ($aula->materias()->count() > 0) {
                return back()->withErrors(['error' => 'No se puede eliminar el aula porque tiene materias asignadas.']);
            }

            DB::beginTransaction();

            // Capturar datos antes de eliminar
            $nombreAula = $aula->nombre;
            $tipoAula = $aula->tipo;
            $capacidadAula = $aula->capacidad;

            $aula->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Eliminó el {$tipoAula} '{$nombreAula}' (Capacidad: {$capacidadAula} personas)",
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.aula.index')
                ->with('success', ucfirst($tipoAula) . ' eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar aula: ' . $e->getMessage()]);
        }
    }
}
