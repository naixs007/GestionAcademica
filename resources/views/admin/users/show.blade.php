<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Detalle del Usuario: <span class="text-primary">{{ $user->name }}</span></h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- Información general del usuario --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-user text-info"></i> Información Personal
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Nombre completo:</dt>
                            <dd class="col-sm-7">
                                <strong>{{ $user->name }}</strong>
                            </dd>

                            <dt class="col-sm-5">Correo electrónico:</dt>
                            <dd class="col-sm-7">
                                <i class="fa-solid fa-envelope text-muted"></i> {{ $user->email }}
                            </dd>

                            {{-- Decano y Admin pueden ver teléfono y estado --}}
                            @if($canViewBasic)
                                <dt class="col-sm-5">Teléfono:</dt>
                                <dd class="col-sm-7">
                                    @if($user->telefono)
                                        <i class="fa-solid fa-phone text-muted"></i> {{ $user->telefono }}
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Estado de cuenta:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        @if($user->estado === 'activo')
                                            <i class="fa-solid fa-circle-check"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        @endif
                                        {{ ucfirst($user->estado ?? 'No definido') }}
                                    </span>
                                </dd>

                                <dt class="col-sm-5">Email verificado:</dt>
                                <dd class="col-sm-7">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-check-circle"></i> Verificado
                                        </span>
                                        <small class="text-muted d-block">{{ $user->email_verified_at->format('d/m/Y') }}</small>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fa-solid fa-exclamation-circle"></i> Sin verificar
                                        </span>
                                    @endif
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Solo Admin ve roles y permisos --}}
            @if($canViewFull)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-shield-halved text-warning"></i> Roles y Permisos
                            </h5>
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Roles asignados:</dt>
                                <dd class="col-sm-7">
                                    @if($user->getRoleNames()->count() > 0)
                                        @foreach($user->getRoleNames() as $roleName)
                                            <span class="badge bg-primary me-1 mb-1">
                                                <i class="fa-solid fa-user-tag"></i> {{ $roleName }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Sin roles asignados</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Total de permisos:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge bg-info">{{ $user->getAllPermissions()->count() }}</span>
                                    <small class="text-muted">(vía roles + directos)</small>
                                </dd>

                                <dt class="col-sm-5">Permisos directos:</dt>
                                <dd class="col-sm-7">
                                    @if($user->permissions->count() > 0)
                                        <span class="badge bg-secondary">{{ $user->permissions->count() }}</span>
                                        <small class="text-muted d-block mt-1">{{ $user->permissions->pluck('name')->join(', ') }}</small>
                                    @else
                                        <span class="text-muted">Ninguno</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Solo Admin ve información del sistema --}}
        @if($canViewFull)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-clock text-secondary"></i> Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <i class="fa-solid fa-calendar-plus text-primary fa-2x mb-2"></i>
                                <h6 class="mb-1">Fecha de Registro</h6>
                                <p class="mb-0 text-muted">{{ $user->created_at->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $user->created_at->format('H:i:s') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <i class="fa-solid fa-pen text-warning fa-2x mb-2"></i>
                                <h6 class="mb-1">Última Actualización</h6>
                                <p class="mb-0 text-muted">{{ $user->updated_at->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $user->updated_at->format('H:i:s') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <i class="fa-solid fa-fingerprint text-success fa-2x mb-2"></i>
                                <h6 class="mb-1">ID de Usuario</h6>
                                <p class="mb-0 text-muted">#{{ $user->id }}</p>
                                <small class="text-muted">Identificador único</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permisos detallados agrupados por categoría --}}
            @if($user->getAllPermissions()->count() > 0)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-key text-warning"></i> Permisos Efectivos del Usuario
                            <span class="badge bg-info float-end">{{ $user->getAllPermissions()->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            // Agrupar permisos por categoría
                            $groupedPermissions = $user->getAllPermissions()->groupBy(function($perm) {
                                $parts = explode('.', $perm->name);
                                return ucfirst($parts[0] ?? 'Otros');
                            });
                        @endphp

                        <div class="row">
                            @foreach($groupedPermissions as $category => $permissions)
                                <div class="col-md-6 col-lg-4 mb-3">
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
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
