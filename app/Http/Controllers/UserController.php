<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        // Protege todas las acciones con autenticación y verificación de email
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the users for the admin area.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // Si es docente, solo mostrar otros docentes
        if ($currentUser->hasRole('docente') && !$currentUser->hasAnyRole(['admin', 'super-admin'])) {
            $users = User::whereHas('roles', function($query) {
                $query->where('name', 'docente');
            })->paginate(15);
        } else {
            // Admin ve todos los usuarios
            $users = User::paginate(15);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function show(User $user): View
    {
        $currentUser = Auth::user();
        $user->load('roles', 'permissions');
        
        // Determinar qué campos mostrar según el rol del usuario actual
        // Admin/Super-admin ven todo
        $canViewFull = $currentUser->hasAnyRole(['admin', 'super-admin']);
        // Decano ve nombre, correo, teléfono y estado
        $canViewBasic = $currentUser->hasRole('decano') || $canViewFull;
        
        return view('admin.users.show', compact('user', 'canViewFull', 'canViewBasic'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Registrar en bitácora (usar columnas de la migración)
        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => $user->name ?? $user->email,
            'metodo' => 'Creación de usuario',
            'descripcion' => 'Usuario creado: ' . $user->name . ' (' . $user->email . ')',
            'direccion_ip' => $request->ip() ?? 'No disponible',
            'navegador' => $request->header('user-agent') ?? 'No disponible',
            'tabla' => 'users',
            'registro_id' => $user->id,
            'fecha_hora' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'estado' => ['nullable', 'in:activo,inactivo'],
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Solo actualizar contraseña si se proporciona
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }
        
        // Solo actualizar teléfono si se proporciona
        if (!empty($data['telefono'])) {
            $updateData['telefono'] = $data['telefono'];
        }
        
        // Solo actualizar estado si se proporciona
        if (!empty($data['estado'])) {
            $updateData['estado'] = $data['estado'];
        }

        $user->update($updateData);

        // Registrar en bitácora (usar columnas de la migración)
        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => $user->name ?? $user->email,
            'metodo' => 'Actualización de usuario',
            'descripcion' => 'Usuario actualizado: ' . $user->name . ' (' . $user->email . ')',
            'direccion_ip' => $request->ip() ?? 'No disponible',
            'navegador' => $request->header('user-agent') ?? 'No disponible',
            'tabla' => 'users',
            'registro_id' => $user->id,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user)
    {
        $userName = $user->name;
        $userEmail = $user->email;

        $userId = $user->id;
        $user->delete();

        // Registrar en bitácora (usar columnas de la migración)
        Bitacora::create([
            'user_id' => Auth::id(),
            'usuario' => $userName . ' (' . $userEmail . ')',
            'metodo' => 'Eliminación de usuario',
            'descripcion' => 'Usuario eliminado: ' . $userName . ' (' . $userEmail . ')',
            'direccion_ip' => $request->ip() ?? 'No disponible',
            'navegador' => $request->header('user-agent') ?? 'No disponible',
            'tabla' => 'users',
            'registro_id' => $userId,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }

    public function editRoles(User $user): View
    {
        // Listas para checkboxes
        $roles = Role::orderBy('name')->get(['id', 'name']);
        $permissions = Permission::orderBy('name')->get(['id', 'name']);

        // IDs actuales del usuario
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('admin.users.edit-roles', compact(
            'user', 'roles', 'permissions', 'userRoleIds', 'userPermissionIds'
        ));
    }

    public function updateRoles(Request $request, User $user)
    {
        // Validación y normalización
        $data = $request->validate([
            'roles' => ['nullable','array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'permissions' => ['nullable','array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $newRoleIds = $data['roles'] ?? [];
        $newPermIds = $data['permissions'] ?? [];

        // Snapshot antes de sincronizar
        $oldRoleIds = $user->roles()->pluck('id')->toArray();
        $oldPermIds = $user->permissions()->pluck('id')->toArray();

        // Sincronizar roles y permisos
        $roles = Role::whereIn('id', $newRoleIds)->where('guard_name', 'web')->get();
        $perms = Permission::whereIn('id', $newPermIds)->where('guard_name', 'web')->get();

        $user->syncRoles($roles);
        $user->syncPermissions($perms);

        // Detectar cambios
        $rolesChanged = $this->idsChanged($oldRoleIds, $newRoleIds);
        $permsChanged = $this->idsChanged($oldPermIds, $newPermIds);

        // Registrar en bitácora si hubo cambios
        if ($rolesChanged || $permsChanged) {
            $detalles = [];

            if ($rolesChanged) {
                $detalles[] = sprintf(
                    'Roles: [%s] -> [%s]',
                    implode(',', $oldRoleIds),
                    implode(',', $newRoleIds)
                );
            }

            if ($permsChanged) {
                $detalles[] = sprintf(
                    'Permisos: [%s] -> [%s]',
                    implode(',', $oldPermIds),
                    implode(',', $newPermIds)
                );
            }

            Bitacora::create([
                'user_id'     => Auth::id(),
                'usuario'     => $user->name ?? $user->email,
                'metodo'      => 'Actualización de roles/permisos',
                'descripcion' => 'Usuario afectado: '.$user->name.' | '.implode(' | ', $detalles),
                'direccion_ip' => $request->ip() ?? 'No disponible',
                'navegador' => $request->header('user-agent') ?? 'No disponible',
                'tabla' => 'users',
                'registro_id' => $user->id,
                'fecha_hora' => now(),
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Roles y permisos actualizados correctamente.');
    }

    public function getRolesData(User $user)
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);
        $permissions = Permission::orderBy('name')->get(['id', 'name']);

        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        $rolesData = $roles->map(function ($role) use ($userRoleIds) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'assigned' => in_array($role->id, $userRoleIds)
            ];
        });

        $permissionsData = $permissions->map(function ($permission) use ($userPermissionIds) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'assigned' => in_array($permission->id, $userPermissionIds)
            ];
        });

        return response()->json([
            'roles' => $rolesData,
            'permissions' => $permissionsData
        ]);
    }

    public function bitacora(Request $request): View
    {
        $bitacora = Bitacora::with('user')
            ->when($request->filled('action'), function($query) use ($request) {
                $term = $request->input('action');
                return $query->where(function($q) use ($term) {
                    $q->where('usuario', 'like', '%'.$term.'%')
                      ->orWhere('descripcion', 'like', '%'.$term.'%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bitacora.index', compact('bitacora'));
    }

    /**
     * Compara arrays de IDs ignorando orden y duplicados.
     */
    private function idsChanged(array $old, array $new): bool
    {
        sort($old);
        sort($new);
        return $old !== $new;
    }
}
