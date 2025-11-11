<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="fa-solid fa-user-edit text-primary"></i> Editar Usuario: <span class="text-primary">{{ $user->name }}</span>
            </h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Información Personal --}}
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-user text-info"></i> Información Personal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-signature"></i> Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required placeholder="Ej: Juan Pérez">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-envelope"></i> Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required placeholder="ejemplo@correo.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @can('usuarios.editar')
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fa-solid fa-phone"></i> Teléfono
                                    </label>
                                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                           value="{{ old('telefono', $user->telefono) }}" placeholder="Ej: +591 12345678">
                                    <small class="text-muted">Dejar en blanco si no desea cambiar</small>
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-bold">
                                        <i class="fa-solid fa-toggle-on"></i> Estado de la Cuenta
                                    </label>
                                    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                        <option value="">-- No modificar --</option>
                                        <option value="activo" {{ old('estado', $user->estado) == 'activo' ? 'selected' : '' }}>
                                            ✅ Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado', $user->estado) == 'inactivo' ? 'selected' : '' }}>
                                            ⛔ Inactivo
                                        </option>
                                    </select>
                                    <small class="text-muted">Dejar en blanco si no desea cambiar</small>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- Seguridad --}}
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-lock text-warning"></i> Seguridad
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle"></i>
                                <strong>Nota:</strong> Solo completa estos campos si deseas cambiar la contraseña. Si los dejas en blanco, la contraseña actual se mantendrá sin cambios.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-key"></i> Nueva Contraseña
                                </label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Mínimo 8 caracteres">
                                <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa-solid fa-check-double"></i> Confirmar Nueva Contraseña
                                </label>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       placeholder="Repite la contraseña">
                            </div>

                            <div class="alert alert-warning mb-0">
                                <i class="fa-solid fa-shield-halved"></i>
                                <strong>Seguridad:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>Usa al menos 8 caracteres</li>
                                    <li>Combina mayúsculas y minúsculas</li>
                                    <li>Incluye números y símbolos</li>
                                </ul>
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
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle"></i> Los campos marcados con <span class="text-danger">*</span> son obligatorios
                        </small>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
