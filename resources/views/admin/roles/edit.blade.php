<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="fa-solid fa-user-tag text-primary"></i> Editar Rol: <span class="text-primary">{{ $role->name }}</span>
            </h2>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Información del Rol --}}
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-info-circle text-info"></i> Información del Rol
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-tag"></i> Nombre del Rol <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $role->name) }}" required placeholder="Ej: Coordinador">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Identificador único del rol</small>
                            </div>

                            <div class="alert alert-info mb-0">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-chart-simple"></i> Estadísticas
                                </h6>
                                <hr>
                                <p class="mb-2">
                                    <strong>Usuarios con este rol:</strong>
                                    <span class="badge bg-success float-end">{{ $role->users()->count() }}</span>
                                </p>
                                <p class="mb-0">
                                    <strong>Permisos actuales:</strong>
                                    <span class="badge bg-info float-end">{{ $role->permissions->count() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Permisos --}}
                <div class="col-md-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fa-solid fa-key text-warning"></i> Permisos Asignados
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                        <i class="fa-solid fa-check-double"></i> Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <i class="fa-solid fa-times"></i> Ninguno
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $assigned = $role->permissions->pluck('id')->toArray();
                                $allPermissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();
                                $groupedPermissions = $allPermissions->groupBy(function($perm) {
                                    $parts = explode('.', $perm->name);
                                    return ucfirst($parts[0] ?? 'Otros');
                                });
                            @endphp

                            <div class="row">
                                @foreach($groupedPermissions as $category => $permissions)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 bg-light">
                                            <h6 class="fw-bold text-uppercase mb-3 text-primary">
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
                                                <span class="badge bg-secondary float-end">{{ $permissions->count() }}</span>
                                            </h6>
                                            @foreach($permissions as $perm)
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                                           id="perm-{{ $perm->id }}" class="form-check-input permission-checkbox"
                                                           {{ in_array($perm->id, old('permissions', $assigned)) ? 'checked' : '' }}>
                                                    <label for="perm-{{ $perm->id }}" class="form-check-label">
                                                        {{ Str::after($perm->name, '.') }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
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
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle"></i> Los cambios afectarán a {{ $role->users()->count() }} usuario(s)
                        </small>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('selectAll');
            const deselectAllBtn = document.getElementById('deselectAll');
            const checkboxes = document.querySelectorAll('.permission-checkbox');

            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });

            deselectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });
        });
    </script>
    @endpush
</x-admin-layout>
