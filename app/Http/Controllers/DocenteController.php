<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docentes = Docente::with('user')->paginate(10);
        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener usuarios que no son docentes aún
        $users = User::whereDoesntHave('docente')->get();
        return view('admin.docentes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:docentes,user_id',
            'categoria' => 'required|string|max:100',
            'profesion' => 'nullable|string|max:150',
        ]);

        try {
            DB::beginTransaction();

            $docente = Docente::create($validated);

            // Asignar rol de docente al usuario si no lo tiene
            $user = User::find($validated['user_id']);
            if (!$user->hasRole('docente')) {
                $user->assignRole('docente');
            }

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Creó el docente '{$user->name}' (Categoría: {$docente->categoria})",
                'metodo' => 'POST',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('success', 'Docente registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar docente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Docente $docente)
    {
        $docente->load(['user', 'materias', 'asistencias']);
        return view('admin.docentes.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        $users = User::whereDoesntHave('docente')
            ->orWhere('id', $docente->user_id)
            ->get();
        return view('admin.docentes.edit', compact('docente', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:docentes,user_id,' . $docente->id,
            'categoria' => 'required|string|max:100',
            'profesion' => 'nullable|string|max:150',
        ]);

        try {
            DB::beginTransaction();

            $oldUserId = $docente->user_id;
            $oldUserName = $docente->user->name;
            $docente->update($validated);

            // Si cambió el usuario, actualizar roles
            if ($oldUserId != $validated['user_id']) {
                // Remover rol del usuario anterior si no tiene otros vínculos
                $oldUser = User::find($oldUserId);
                if ($oldUser && !$oldUser->docente()->exists()) {
                    $oldUser->removeRole('docente');
                }

                // Asignar rol al nuevo usuario
                $newUser = User::find($validated['user_id']);
                if (!$newUser->hasRole('docente')) {
                    $newUser->assignRole('docente');
                }
            }

            // Registrar en bitácora
            $currentUser = User::find($validated['user_id']);
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Actualizó el docente '{$oldUserName}' a '{$currentUser->name}' (Categoría: {$docente->categoria})",
                'metodo' => 'PUT',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('success', 'Docente actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar docente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Docente $docente)
    {
        try {
            DB::beginTransaction();

            // Capturar datos antes de eliminar
            $userId = $docente->user_id;
            $nombreDocente = $docente->user->name;
            $categoriaDocente = $docente->categoria;

            $docente->delete();

            // Remover rol de docente si el usuario ya no tiene el vínculo
            $user = User::find($userId);
            if ($user && !$user->docente()->exists()) {
                $user->removeRole('docente');
            }

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Eliminó el docente '{$nombreDocente}' (Categoría: {$categoriaDocente})",
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('success', 'Docente eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar docente: ' . $e->getMessage()]);
        }
    }
}
