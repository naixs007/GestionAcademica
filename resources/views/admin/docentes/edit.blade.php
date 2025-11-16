<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-edit text-warning"></i> Editar Docente
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
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-edit"></i> Actualizar Información del Docente
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.docentes.update', $docente) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Columna izquierda - Datos del Usuario --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-user"></i> Datos del Usuario
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            {{-- Usuario --}}
                                            <div class="mb-3">
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
                                                                {{ old('user_id', $docente->user_id) == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('user_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-info-circle"></i>
                                                    Usuario actual: <strong>{{ $docente->user->name }}</strong>
                                                </small>
                                            </div>

                                            {{-- Información del usuario actual --}}
                                            <div class="alert alert-info">
                                                <small>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <strong>Email:</strong> {{ $docente->user->email }}<br>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <strong>Teléfono:</strong> {{ $docente->user->telefono ?? 'No registrado' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Carga Máxima --}}
                                    <div class="mb-4">
                                        <label for="carga_maxima_horas" class="form-label">
                                            <i class="fa-solid fa-gauge-high text-danger"></i>
                                            <strong>Carga Máxima (horas/semana)</strong> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="carga_maxima_horas"
                                               id="carga_maxima_horas"
                                               class="form-control @error('carga_maxima_horas') is-invalid @enderror"
                                               value="{{ old('carga_maxima_horas', $docente->carga_maxima_horas ?? 24.00) }}"
                                               min="1"
                                               max="48"
                                               step="0.01"
                                               required>
                                        @error('cargaHoraria')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="progress mt-2" style="height: 20px;">
                                            <div class="progress-bar bg-info"
                                                 role="progressbar"
                                                 style="width: {{ ($docente->cargaHoraria / 24) * 100 }}%"
                                                 aria-valuenow="{{ $docente->cargaHoraria }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="24">
                                                {{ $docente->cargaHoraria }} hrs
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i>
                                            Entre 1 y 24 horas semanales
                                        </small>
                                    </div>
                                </div>

                                {{-- Columna derecha - Datos Académicos --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-graduation-cap"></i> Datos Académicos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            {{-- Categoría --}}
                                            <div class="mb-3">
                                                <label for="categoria" class="form-label">
                                                    <i class="fa-solid fa-tag text-warning"></i>
                                                    <strong>Categoría</strong> <span class="text-danger">*</span>
                                                </label>
                                                <select name="categoria" id="categoria"
                                                        class="form-select @error('categoria') is-invalid @enderror"
                                                        required>
                                                    <option value="">Seleccione una categoría</option>
                                                    <option value="Titular" {{ old('categoria', $docente->categoria) == 'Titular' ? 'selected' : '' }}>
                                                        Titular
                                                    </option>
                                                    <option value="Adjunto" {{ old('categoria', $docente->categoria) == 'Adjunto' ? 'selected' : '' }}>
                                                        Adjunto
                                                    </option>
                                                    <option value="Auxiliar" {{ old('categoria', $docente->categoria) == 'Auxiliar' ? 'selected' : '' }}>
                                                        Auxiliar
                                                    </option>
                                                    <option value="Contratado" {{ old('categoria', $docente->categoria) == 'Contratado' ? 'selected' : '' }}>
                                                        Contratado
                                                    </option>
                                                    <option value="Invitado" {{ old('categoria', $docente->categoria) == 'Invitado' ? 'selected' : '' }}>
                                                        Invitado
                                                    </option>
                                                </select>
                                                @error('categoria')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Profesión --}}
                                            <div class="mb-3">
                                                <label for="profesion" class="form-label">
                                                    <i class="fa-solid fa-graduation-cap text-success"></i>
                                                    <strong>Profesión</strong>
                                                </label>
                                                <input type="text"
                                                       name="profesion"
                                                       id="profesion"
                                                       class="form-control @error('profesion') is-invalid @enderror"
                                                       value="{{ old('profesion', $docente->profesion) }}"
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

                                    {{-- Estadísticas --}}
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-chart-bar"></i> Estadísticas
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-book text-success"></i> Materias:
                                                </span>
                                                <strong>{{ $docente->materias->count() }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-calendar-check text-warning"></i> Asistencias:
                                                </span>
                                                <strong>{{ $docente->asistencias->count() }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class="fa-solid fa-clock text-info"></i> Registro:
                                                </span>
                                                <strong>{{ $docente->created_at->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Advertencia de cambios --}}
                            <div class="alert alert-warning mb-4">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <strong>Importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Si cambia el usuario asociado, el rol de "docente" se transferirá al nuevo usuario.</li>
                                    <li>El usuario anterior perderá el rol de "docente" si no tiene otros vínculos.</li>
                                    <li>Las materias y asistencias permanecerán asociadas a este registro de docente.</li>
                                </ul>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Actualizar Docente
                                </button>
                                <a href="{{ route('admin.docentes.show', $docente) }}"
                                   class="btn btn-info btn-lg">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('admin.docentes.index') }}"
                                   class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Información del sistema --}}
                <div class="card mt-4 border-secondary">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa-solid fa-info-circle text-secondary"></i> Información del Sistema
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">ID Docente:</small>
                                <p><strong>#{{ $docente->id }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Última actualización:</small>
                                <p><strong>{{ $docente->updated_at->format('d/m/Y H:i:s') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Actualizar la barra de progreso cuando cambie la carga horaria
        document.getElementById('cargaHoraria').addEventListener('input', function() {
            const value = this.value;
            const progressBar = document.querySelector('.progress-bar');
            const percentage = (value / 24) * 100;
            progressBar.style.width = percentage + '%';
            progressBar.textContent = value + ' hrs';
        });
    </script>
</x-admin-layout>
