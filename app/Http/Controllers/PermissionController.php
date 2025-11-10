<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::withCount('roles')->orderBy('name')->paginate(10);
    return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:permissions,name'],
        ]);

        $permission = Permission::create(['name' => $data['name'], 'guard_name' => 'web']);

        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Creación de permiso',
            'descripcion' => 'Permiso creado: ' . $permission->name,
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'permissions',
            'registro_id' => $permission->id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.permissions.index')->with('status','Permiso creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
    return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
    return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:permissions,name,'.$permission->id],
        ]);

        $permission->update(['name' => $data['name']]);

        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Actualización de permiso',
            'descripcion' => 'Permiso actualizado: ' . $permission->name,
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'permissions',
            'registro_id' => $permission->id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.permissions.index')->with('status','Permiso actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $id = $permission->id;
        $name = $permission->name;
        $permission->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Eliminación de permiso',
            'descripcion' => 'Permiso eliminado: ' . $name,
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'permissions',
            'registro_id' => $id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.permissions.index')->with('status','Permiso eliminado.');
    }
}
