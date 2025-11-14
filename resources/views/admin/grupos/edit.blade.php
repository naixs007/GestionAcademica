<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-edit text-warning"></i> Editar Grupo
            </h2>
            <a href="{{ route('admin.grupos.index') }}" class="btn btn-outline-secondary">
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
                            <i class="fa-solid fa-users-rectangle"></i> Actualizar Información del Grupo
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.grupos.update', $grupo) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Columna Izquierda --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-info-circle"></i> Datos Básicos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            {{-- Nombre del Grupo --}}
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">
                                                    <i class="fa-solid fa-tag text-primary"></i>
                                                    <strong>Nombre del Grupo</strong> <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       name="nombre"
                                                       id="nombre"
                                                       class="form-control @error('nombre') is-invalid @enderror"
                                                       value="{{ old('nombre', $grupo->nombre) }}"
                                                       maxlength="100"
                                                       required>
                                                @error('nombre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Cupo Máximo --}}
                                            <div class="mb-3">
                                                <label for="cupo_maximo" class="form-label">
                                                    <i class="fa-solid fa-users text-info"></i>
                                                    <strong>Cupo Máximo</strong> <span class="text-danger">*</span>
                                                </label>
                                                <input type="number"
                                                       name="cupo_maximo"
                                                       id="cupo_maximo"
                                                       class="form-control @error('cupo_maximo') is-invalid @enderror"
                                                       value="{{ old('cupo_maximo', $grupo->cupo_maximo) }}"
                                                       min="1"
                                                       max="120"
                                                       required>
                                                @error('cupo_maximo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                {{-- Barra de progreso --}}
                                                <div class="progress mt-2" style="height: 20px;">
                                                    <div class="progress-bar bg-info"
                                                         role="progressbar"
                                                         style="width: {{ ($grupo->cupo_maximo / 120) * 100 }}%"
                                                         aria-valuenow="{{ $grupo->cupo_maximo }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="120">
                                                        {{ $grupo->cupo_maximo }} estudiantes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Columna Derecha --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-calendar-check"></i> Asignaciones
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <i class="fa-solid fa-info-circle"></i>
                                                <strong>Nota:</strong> Las asignaciones de materias y docentes se gestionan desde el módulo de Carga Académica.
                                            </div>

                                            @if($grupo->cargasAcademicas && $grupo->cargasAcademicas->count() > 0)
                                                <label class="form-label"><strong>Asignaciones actuales:</strong></label>
                                                <ul class="list-group">
                                                    @foreach($grupo->cargasAcademicas as $carga)
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-book text-primary"></i> {{ $carga->materia->nombre }}
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fa-solid fa-user"></i> {{ $carga->docente->user->name }}
                                                            </small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted mb-0">No hay asignaciones para este grupo.</p>
                                                </div>
                                            @endif
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
                                                    <i class="fa-solid fa-users text-info"></i> Cupo Máximo:
                                                </span>
                                                <strong>{{ $grupo->cupo_maximo }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-user-check text-success"></i> Inscritos:
                                                </span>
                                                <strong>0</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class="fa-solid fa-calendar text-warning"></i> Creado:
                                                </span>
                                                <strong>{{ $grupo->created_at->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Advertencia --}}
                            <div class="alert alert-warning mb-4">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <strong>Importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Si cambia la materia asignada, verifique que sea coherente con el plan de estudios.</li>
                                    <li>El cupo máximo no debe ser menor que el número de estudiantes ya inscritos.</li>
                                    <li>Cualquier cambio afectará inmediatamente a los registros asociados.</li>
                                </ul>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Actualizar Grupo
                                </button>
                                <a href="{{ route('admin.grupos.index') }}"
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
                            <div class="col-md-4">
                                <small class="text-muted">ID Grupo:</small>
                                <p><strong>#{{ $grupo->id }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Creado:</small>
                                <p><strong>{{ $grupo->created_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Última actualización:</small>
                                <p><strong>{{ $grupo->updated_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Actualizar la barra de progreso cuando cambie el cupo máximo
        document.getElementById('cupo_maximo').addEventListener('input', function() {
            const value = this.value;
            const progressBar = document.querySelector('.progress-bar');
            const percentage = (value / 120) * 100;
            progressBar.style.width = percentage + '%';
            progressBar.textContent = value + ' estudiantes';
        });
    </script>
</x-admin-layout>
