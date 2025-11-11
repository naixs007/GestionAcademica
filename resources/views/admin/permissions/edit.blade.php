<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="fa-solid fa-key text-primary"></i> Editar Permiso: <span class="text-primary">{{ $permission->name }}</span>
            </h2>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-triangle"></i> <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Formulario de Edición --}}
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-edit text-info"></i> Información del Permiso
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-tag"></i> Nombre del Permiso <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $permission->name) }}" required 
                                       placeholder="Ej: categoria.accion">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Formato: categoria.accion (ej: usuarios.ver)</small>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <strong>Importante:</strong> Cambiar el nombre del permiso puede afectar el funcionamiento del sistema. Asegúrate de actualizar las referencias en el código si es necesario.
                            </div>

                            @php
                                $parts = explode('.', $permission->name);
                                $category = ucfirst($parts[0] ?? 'General');
                                $action = ucfirst($parts[1] ?? '-');
                            @endphp

                            <div class="alert alert-info mb-0">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-info-circle"></i> Interpretación Actual
                                </h6>
                                <hr>
                                <p class="mb-2">
                                    <strong>Categoría:</strong>
                                    <span class="badge bg-secondary">
                                        @switch($category)
                                            @case('Usuarios') <i class="fa-solid fa-users"></i> @break
                                            @case('Roles') <i class="fa-solid fa-user-tag"></i> @break
                                            @case('Permissions') <i class="fa-solid fa-key"></i> @break
                                            @case('Materias') <i class="fa-solid fa-book"></i> @break
                                            @case('Horarios') <i class="fa-solid fa-clock"></i> @break
                                            @case('Aulas') <i class="fa-solid fa-building"></i> @break
                                            @case('Asistencia') <i class="fa-solid fa-clipboard-check"></i> @break
                                            @case('Reportes') <i class="fa-solid fa-chart-bar"></i> @break
                                            @case('Bitacora') <i class="fa-solid fa-file-lines"></i> @break
                                            @default <i class="fa-solid fa-folder"></i>
                                        @endswitch
                                        {{ $category }}
                                    </span>
                                </p>
                                <p class="mb-0">
                                    <strong>Acción:</strong>
                                    <span class="badge bg-info">{{ str_replace('_', ' ', $action) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información de Impacto --}}
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-chart-simple text-warning"></i> Impacto del Cambio
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border">
                                <h6 class="fw-bold mb-3">
                                    <i class="fa-solid fa-users"></i> Este permiso está asignado a:
                                </h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><i class="fa-solid fa-user-tag"></i> Roles:</span>
                                        <span class="badge bg-success fs-6">{{ $permission->roles->count() }}</span>
                                    </div>
                                    @if($permission->roles->count() > 0)
                                        <div class="ms-3">
                                            @foreach($permission->roles as $role)
                                                <span class="badge bg-primary me-1 mb-1">{{ $role->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $usersWithPermission = \App\Models\User::permission($permission->name)->count();
                                @endphp
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fa-solid fa-users"></i> Usuarios afectados:</span>
                                        <span class="badge bg-info fs-6">{{ $usersWithPermission }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fa-solid fa-lightbulb text-warning"></i> Recomendaciones
                                    </h6>
                                    <ul class="mb-0">
                                        <li class="mb-2">✅ Usa nombres descriptivos y consistentes</li>
                                        <li class="mb-2">✅ Mantén el formato: <code>categoria.accion</code></li>
                                        <li class="mb-2">✅ Verifica que no exista duplicado</li>
                                        <li class="mb-0">⚠️ Evita cambiar permisos del sistema</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle"></i> Los cambios afectarán a {{ $usersWithPermission }} usuario(s)
                        </small>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
