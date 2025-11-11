<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Detalle del Permiso: <span class="text-primary">{{ $permission->name }}</span></h2>
            <div>
            {{--    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>--}}
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        {{-- Información general del permiso --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-info-circle text-info"></i> Información General
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre del permiso:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-primary fs-6">{{ $permission->name }}</span>
                            </dd>

                            @php
                                $parts = explode('.', $permission->name);
                                $category = ucfirst($parts[0] ?? 'General');
                                $action = ucfirst($parts[1] ?? '-');
                            @endphp

                            <dt class="col-sm-5">Categoría:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-secondary">
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
                                </span>
                            </dd>

                            <dt class="col-sm-5">Acción:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-info">
                                    @switch($action)
                                        @case('Ver')
                                            <i class="fa-solid fa-eye"></i>
                                            @break
                                        @case('Crear')
                                            <i class="fa-solid fa-plus"></i>
                                            @break
                                        @case('Editar')
                                            <i class="fa-solid fa-edit"></i>
                                            @break
                                        @case('Eliminar')
                                            <i class="fa-solid fa-trash"></i>
                                            @break
                                        @case('Asignar_roles')
                                            <i class="fa-solid fa-user-plus"></i>
                                            @break
                                        @case('Remover_roles')
                                            <i class="fa-solid fa-user-minus"></i>
                                            @break
                                        @case('Registrar')
                                            <i class="fa-solid fa-clipboard"></i>
                                            @break
                                        @case('Descargar')
                                            <i class="fa-solid fa-download"></i>
                                            @break
                                        @default
                                            <i class="fa-solid fa-cog"></i>
                                    @endswitch
                                    {{ str_replace('_', ' ', $action) }}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Roles asignados:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-success">{{ $permission->roles->count() }}</span>
                            </dd>

                            <dt class="col-sm-5">Creado:</dt>
                            <dd class="col-sm-7 text-muted">{{ $permission->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-5">Última actualización:</dt>
                            <dd class="col-sm-7 text-muted">{{ $permission->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-shield-halved text-warning"></i> Descripción del Permiso
                        </h5>
                        <div class="alert alert-light border">
                            <p class="mb-2"><strong>Este permiso permite:</strong></p>
                            <p class="mb-0">
                                @if(str_contains($permission->name, 'ver'))
                                    👁️ <strong>Visualizar</strong> información relacionada con <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'crear'))
                                    ➕ <strong>Crear</strong> nuevos registros de <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'editar'))
                                    ✏️ <strong>Editar</strong> registros existentes de <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'eliminar'))
                                    🗑️ <strong>Eliminar</strong> registros de <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'asignar'))
                                    👤➕ <strong>Asignar</strong> roles/permisos relacionados con <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'registrar'))
                                    📝 <strong>Registrar</strong> información de <strong>{{ $category }}</strong>.
                                @elseif(str_contains($permission->name, 'descargar'))
                                    💾 <strong>Descargar</strong> datos de <strong>{{ $category }}</strong>.
                                @else
                                    🔧 Realizar acciones específicas relacionadas con <strong>{{ $category }}</strong>.
                                @endif
                            </p>
                        </div>

                        <div class="alert alert-info border-info mb-0">
                            <small>
                                <i class="fa-solid fa-info-circle"></i>
                                <strong>Nota:</strong> Los usuarios que tengan este permiso (directamente o a través de un rol) podrán ejecutar esta acción en el sistema.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Roles que tienen este permiso --}}
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fa-solid fa-user-tag text-warning"></i> Roles con este Permiso
                    <span class="badge bg-success float-end">{{ $permission->roles->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($permission->roles->count() > 0)
                    <div class="row">
                        @foreach($permission->roles as $role)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">
                                            <i class="fa-solid fa-user-tag text-primary"></i>
                                            {{ $role->name }}
                                        </h6>
                                        <p class="card-text mb-2">
                                            <small class="text-muted">
                                                <i class="fa-solid fa-users"></i>
                                                {{ $role->users()->count() }} usuario(s) con este rol
                                            </small>
                                        </p>
                                        <p class="card-text mb-0">
                                            <small class="text-muted">
                                                <i class="fa-solid fa-key"></i>
                                                {{ $role->permissions()->count() }} permiso(s) total
                                            </small>
                                        </p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye"></i> Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        Este permiso no está asignado a ningún rol. Los usuarios solo podrán tenerlo si se les asigna directamente.
                    </div>
                @endif
            </div>
        </div>

        {{-- Acciones adicionales
        <div class="mt-4">
            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                <i class="fa-solid fa-edit"></i> Editar Permiso
            </a>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-list"></i> Ver todos los permisos
            </a>
            @can('permissions.eliminar')
                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este permiso? Los roles perderán este permiso.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> Eliminar Permiso
                    </button>
                </form>
            @endcan
        </div>--}}
    </div>
</x-admin-layout>
