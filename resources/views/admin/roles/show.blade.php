<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Detalle del Rol: <span class="text-primary">{{ $role->name }}</span></h2>
            <div>
             {{--   <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>--}}
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        {{-- Información general del rol --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-info-circle text-info"></i> Información General
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre del rol:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-primary fs-6">{{ $role->name }}</span>
                            </dd>

                            <dt class="col-sm-5">Usuarios asignados:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-success">{{ $role->users()->count() }}</span>
                            </dd>

                            <dt class="col-sm-5">Total de permisos:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                            </dd>

                            <dt class="col-sm-5">Creado:</dt>
                            <dd class="col-sm-7 text-muted">{{ $role->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-5">Última actualización:</dt>
                            <dd class="col-sm-7 text-muted">{{ $role->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-users text-success"></i> Usuarios con este Rol
                        </h5>
                        @if($role->users()->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($role->users()->limit(5)->get() as $user)
                                    <li class="list-group-item px-0 py-2">
                                        <i class="fa-solid fa-user text-secondary"></i> {{ $user->name }}
                                        <small class="text-muted">({{ $user->email }})</small>
                                    </li>
                                @endforeach
                                @if($role->users()->count() > 5)
                                    <li class="list-group-item px-0 py-2 text-muted">
                                        <small>... y {{ $role->users()->count() - 5 }} usuarios más</small>
                                    </li>
                                @endif
                            </ul>
                        @else
                            <p class="text-muted mb-0">
                                <i class="fa-solid fa-info-circle"></i> No hay usuarios asignados a este rol.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Permisos agrupados por categoría --}}
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fa-solid fa-key text-warning"></i> Permisos Asignados
                    <span class="badge bg-info float-end">{{ $role->permissions->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    @php
                        // Agrupar permisos por categoría (parte antes del punto)
                        $groupedPermissions = $role->permissions->groupBy(function($perm) {
                            $parts = explode('.', $perm->name);
                            return ucfirst($parts[0] ?? 'Otros');
                        });
                    @endphp

                    <div class="row">
                        @foreach($groupedPermissions as $category => $permissions)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <h6 class="fw-bold text-uppercase mb-3 text-primary">
                                        @switch($category)
                                            @case('Usuarios')
                                                <i class="fa-solid fa-users"></i>
                                                @break
                                            @case('Roles')
                                                <i class="fa-solid fa-user-tag"></i>
                                                @break
                                            @case('Permissions')
                                                <i class="fa-solid fa-key"></i>
                                                @break
                                            @case('Materias')
                                                <i class="fa-solid fa-book"></i>
                                                @break
                                            @case('Horarios')
                                                <i class="fa-solid fa-clock"></i>
                                                @break
                                            @case('Aulas')
                                                <i class="fa-solid fa-building"></i>
                                                @break
                                            @case('Asistencia')
                                                <i class="fa-solid fa-clipboard-check"></i>
                                                @break
                                            @case('Reportes')
                                                <i class="fa-solid fa-chart-bar"></i>
                                                @break
                                            @case('Bitacora')
                                                <i class="fa-solid fa-file-lines"></i>
                                                @break
                                            @default
                                                <i class="fa-solid fa-folder"></i>
                                        @endswitch
                                        {{ $category }}
                                        <span class="badge bg-secondary float-end">{{ $permissions->count() }}</span>
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($permissions as $perm)
                                            <li class="mb-2">
                                                <i class="fa-solid fa-check-circle text-success"></i>
                                                <span class="ms-1">{{ Str::after($perm->name, '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        Este rol no tiene permisos asignados.
                    </div>
                @endif
            </div>
        </div>

        {{-- Acciones adicionales
        <div class="mt-4">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                <i class="fa-solid fa-edit"></i> Editar Rol
            </a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-list"></i> Ver todos los roles
            </a>
            @can('roles.eliminar')
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este rol? Los usuarios perderán estos permisos.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> Eliminar Rol
                    </button>
                </form>
            @endcan
        </div>--}}
    </div>
</x-admin-layout>
