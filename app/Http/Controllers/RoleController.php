<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;

class RoleController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
        // $this->authorizeResource(User::class);
    }

    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        $templates = config('role_templates.templates');
        return view('admin.roles.create', compact('permissions', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:roles,name'],
            'template' => ['nullable','string','in:admin,decano,docente,custom'],
            'permissions' => ['nullable','array'],
            'permissions.*' => ['integer','exists:permissions,id'],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        // Si se seleccionó una plantilla, cargar permisos de la plantilla
        if (!empty($data['template']) && $data['template'] !== 'custom') {
            $template = config("role_templates.templates.{$data['template']}");
            if ($template && !empty($template['permissions'])) {
                $templatePerms = Permission::whereIn('name', $template['permissions'])->get();
                $role->syncPermissions($templatePerms);
            }
        } elseif (!empty($data['permissions'])) {
            // Si no hay plantilla o es custom, usar permisos seleccionados manualmente
            $perms = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($perms);
        }

        // Bitacora
        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Creación de rol',
            'descripcion' => 'Rol creado: ' . $role->name . ($data['template'] ?? ' (personalizado)'),
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'roles',
            'registro_id' => $role->id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('status','Rol creado correctamente.');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.edit', compact('role','permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:roles,name,'.$role->id],
            'permissions' => ['nullable','array'],
            'permissions.*' => ['integer','exists:permissions,id'],
        ]);

        $role->update(['name' => $data['name']]);

        $perms = Permission::whereIn('id', $data['permissions'] ?? [])->get();
        $role->syncPermissions($perms);

        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Actualización de rol',
            'descripcion' => 'Rol actualizado: ' . $role->name,
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'roles',
            'registro_id' => $role->id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('status','Rol actualizado.');
    }

    public function destroy(Role $role)
    {
        $id = $role->id;
        $name = $role->name;
        $role->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => Auth::user()?->name ?? Auth::id(),
            'metodo' => 'Eliminación de rol',
            'descripcion' => 'Rol eliminado: ' . $name,
            'direccion_ip' => request()->ip() ?? 'No disponible',
            'navegador' => request()->header('user-agent') ?? 'No disponible',
            'tabla' => 'roles',
            'registro_id' => $id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('status','Rol eliminado.');
    }

    /**
     * API endpoint para obtener permisos de una plantilla
     */
    public function getTemplatePermissions($template)
    {
        $templateData = config("role_templates.templates.{$template}");
        
        if (!$templateData) {
            return response()->json(['error' => 'Plantilla no encontrada'], 404);
        }

        $permissions = Permission::whereIn('name', $templateData['permissions'] ?? [])->get(['id', 'name']);
        
        return response()->json([
            'permissions' => $permissions,
            'description' => $templateData['description'] ?? ''
        ]);
    }
}

