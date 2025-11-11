<x-admin-layout>
    <div class="container py-4">
        <h2 class="h4 mb-4">Gestión de Usuarios y Seguridad</h2>

        <ul class="nav nav-tabs" id="securityTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">Usuarios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="false">Roles</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab" aria-controls="permissions" aria-selected="false">Permisos</button>
            </li>
        </ul>
        <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom shadow-sm" id="securityTabsContent">
            <!-- Usuarios -->
            <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Usuarios</h5>
                    @can('usuarios.crear')
                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">Crear usuario</a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Creado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->getRoleNames()->join(', ') }}</td>
                                    <td>{{ $u->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        @can('usuarios.editar')
                                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-outline-secondary btn-sm">Editar</a>
                                        @endcan
                                        @can('usuarios.asignar_roles')
                                            <a href="{{ route('admin.user.roles.edit', $u) }}" class="btn btn-outline-secondary btn-sm">Roles/Permisos</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Sin usuarios.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $users->fragment('users')->links() }}
                </div>
            </div>

            <!-- Roles -->
            <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Roles</h5>
                    @can('roles.crear')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">Crear rol</a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Usuarios</th>
                                <th class="text-center">Permisos</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $r)
                                <tr>
                                    <td>{{ $r->name }}</td>
                                    <td class="text-center">{{ $r->users_count }}</td>
                                    <td class="text-center">{{ $r->permissions_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.roles.show', $r) }}" class="btn btn-outline-secondary btn-sm">Ver</a>
                                        <a href="{{ route('admin.roles.edit', $r) }}" class="btn btn-outline-secondary btn-sm">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Sin roles.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $roles->fragment('roles')->links() }}
                </div>
            </div>

            <!-- Permisos -->
            <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Permisos</h5>
                    @can('permissions.crear')
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-primary">Crear permiso</a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Roles</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td class="text-center">{{ $p->roles_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.permissions.edit', $p) }}" class="btn btn-outline-secondary btn-sm">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">Sin permisos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $permissions->fragment('permissions')->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Activar tab según fragmento (al cambiar de página de paginación con fragment)
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            if(hash) {
                const triggerEl = document.querySelector(`button[data-bs-target='${hash}']`);
                if (triggerEl) {
                    new bootstrap.Tab(triggerEl).show();
                }
            }
        });
    </script>
</x-admin-layout>
