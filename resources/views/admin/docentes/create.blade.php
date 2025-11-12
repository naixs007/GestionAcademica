<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-plus-circle text-success"></i> Registrar Nuevo Docente
            </h2>
            <a href="{{ route('admin.docentes.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-triangle"></i> Errores de validación
                </h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-plus"></i> Información del Docente
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.docentes.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- Columna izquierda --}}
                                <div class="col-md-6">
                                    {{-- Usuario --}}
                                    <div class="mb-4">
                                        <label for="user_id" class="form-label">
                                            <i class="fa-solid fa-user text-primary"></i> 
                                            <strong>Usuario</strong> <span class="text-danger">*</span>
                                        </label>
                                        <select name="user_id" id="user_id" 
                                                class="form-select @error('user_id') is-invalid @enderror" 
                                                required>
                                            <option value="">Seleccione un usuario</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" 
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i> 
                                            Seleccione el usuario que será registrado como docente
                                        </small>
                                    </div>

                                    {{-- Carga Horaria --}}
                                    <div class="mb-4">
                                        <label for="cargaHoraria" class="form-label">
                                            <i class="fa-solid fa-clock text-info"></i> 
                                            <strong>Carga Horaria (horas/semana)</strong> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="cargaHoraria" 
                                               id="cargaHoraria" 
                                               class="form-control @error('cargaHoraria') is-invalid @enderror"
                                               value="{{ old('cargaHoraria') }}"
                                               min="1"
                                               max="48"
                                               placeholder="Ej: 40"
                                               required>
                                        @error('cargaHoraria')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i> 
                                            Entre 1 y 48 horas semanales
                                        </small>
                                    </div>
                                </div>

                                {{-- Columna derecha --}}
                                <div class="col-md-6">
                                    {{-- Categoría --}}
                                    <div class="mb-4">
                                        <label for="categoria" class="form-label">
                                            <i class="fa-solid fa-tag text-warning"></i> 
                                            <strong>Categoría</strong> <span class="text-danger">*</span>
                                        </label>
                                        <select name="categoria" id="categoria" 
                                                class="form-select @error('categoria') is-invalid @enderror" 
                                                required>
                                            <option value="">Seleccione una categoría</option>
                                            <option value="Titular" {{ old('categoria') == 'Titular' ? 'selected' : '' }}>
                                                Titular
                                            </option>
                                            <option value="Adjunto" {{ old('categoria') == 'Adjunto' ? 'selected' : '' }}>
                                                Adjunto
                                            </option>
                                            <option value="Auxiliar" {{ old('categoria') == 'Auxiliar' ? 'selected' : '' }}>
                                                Auxiliar
                                            </option>
                                            <option value="Contratado" {{ old('categoria') == 'Contratado' ? 'selected' : '' }}>
                                                Contratado
                                            </option>
                                            <option value="Invitado" {{ old('categoria') == 'Invitado' ? 'selected' : '' }}>
                                                Invitado
                                            </option>
                                        </select>
                                        @error('categoria')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i> 
                                            Categoría del docente en la institución
                                        </small>
                                    </div>

                                    {{-- Profesión --}}
                                    <div class="mb-4">
                                        <label for="profesion" class="form-label">
                                            <i class="fa-solid fa-graduation-cap text-success"></i> 
                                            <strong>Profesión</strong>
                                        </label>
                                        <input type="text" 
                                               name="profesion" 
                                               id="profesion" 
                                               class="form-control @error('profesion') is-invalid @enderror"
                                               value="{{ old('profesion') }}"
                                               maxlength="150"
                                               placeholder="Ej: Ingeniero de Sistemas">
                                        @error('profesion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i> 
                                            Profesión o título académico (opcional)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Información adicional --}}
                            <div class="alert alert-info mb-4">
                                <i class="fa-solid fa-lightbulb"></i>
                                <strong>Nota:</strong> Al registrar un docente, se le asignará automáticamente el rol de "docente" 
                                en el sistema si aún no lo tiene.
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Registrar Docente
                                </button>
                                <a href="{{ route('admin.docentes.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tarjeta de ayuda --}}
                <div class="card mt-4 border-primary">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa-solid fa-question-circle text-primary"></i> Ayuda
                        </h6>
                        <ul class="mb-0">
                            <li><strong>Usuario:</strong> Debe seleccionar un usuario existente que no esté registrado como docente.</li>
                            <li><strong>Carga Horaria:</strong> Especifica las horas semanales de trabajo del docente.</li>
                            <li><strong>Categoría:</strong> Indica la categoría o nivel del docente en la institución.</li>
                            <li><strong>Profesión:</strong> Campo opcional para registrar la profesión o título del docente.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
