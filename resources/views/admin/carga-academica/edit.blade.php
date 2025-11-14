<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-edit text-warning"></i> Editar Carga Académica
            </h2>
            <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-circle"></i> Error al actualizar carga académica
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
            {{-- Información Actual --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-info-circle"></i> Asignación Actual
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-user-tie"></i> Docente:
                            </small>
                            <p class="mb-0"><strong>{{ $cargaAcademica->docente->user->name }}</strong></p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-book"></i> Materia:
                            </small>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $cargaAcademica->materia->sigla }}</span>
                                {{ $cargaAcademica->materia->nombre }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-users"></i> Grupo:
                            </small>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $cargaAcademica->grupo->nombre }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-door-open"></i> Aula:
                            </small>
                            <p class="mb-0">
                                <strong>{{ $cargaAcademica->aula->codigo }}</strong>
                                <span class="badge bg-secondary ms-1">{{ $cargaAcademica->aula->tipo }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-calendar"></i> Gestión:
                            </small>
                            <p class="mb-0"><strong>{{ $cargaAcademica->gestion }} - Período {{ $cargaAcademica->periodo }}</strong></p>
                        </div>

                        <div>
                            <small class="text-muted">
                                <i class="fa-solid fa-clock"></i> Horario:
                            </small>
                            <p class="mb-0">
                                {{ substr($cargaAcademica->horario->hora_inicio, 0, 5) }} -
                                {{ substr($cargaAcademica->horario->hora_fin, 0, 5) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    <strong>Nota:</strong> Modifique los campos necesarios y guarde los cambios.
                </div>
            </div>

            {{-- Formulario de Edición --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-wpforms"></i> Datos de la Asignación
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.carga-academica.update', $cargaAcademica->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Seleccionar Docente --}}
                            <div class="mb-4">
                                <label for="docente_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-chalkboard-user text-primary"></i> Docente <span class="text-danger">*</span>
                                </label>
                                <select name="docente_id" id="docente_id" class="form-select @error('docente_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar docente --</option>
                                    @foreach($docentes as $docente)
                                        <option value="{{ $docente->id }}"
                                                {{ old('docente_id', $cargaAcademica->docente_id) == $docente->id ? 'selected' : '' }}>
                                            {{ $docente->user->name }} - {{ $docente->categoria }} ({{ $docente->cargaHoraria }} hrs/semana)
                                        </option>
                                    @endforeach
                                </select>
                                @error('docente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Seleccionar Materia --}}
                            <div class="mb-4">
                                <label for="materia_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-book text-primary"></i> Materia <span class="text-danger">*</span>
                                </label>
                                <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar materia --</option>
                                    @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}"
                                                {{ old('materia_id', $cargaAcademica->materia_id) == $materia->id ? 'selected' : '' }}>
                                            {{ $materia->codigo }} - {{ $materia->nombre }}
                                            ({{ $materia->cargaHoraria }} hrs - {{ $materia->nivel }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('materia_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Seleccionar Grupo --}}
                            <div class="mb-4">
                                <label for="grupo_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-users-rectangle text-primary"></i> Grupo <span class="text-danger">*</span>
                                </label>
                                <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar grupo --</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}"
                                                {{ old('grupo_id', $cargaAcademica->grupo_id) == $grupo->id ? 'selected' : '' }}>
                                            {{ $grupo->nombre }} - Cupo: {{ $grupo->cupo_maximo }} estudiantes
                                        </option>
                                    @endforeach
                                </select>
                                @error('grupo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Seleccionar Horario --}}
                            <div class="mb-4">
                                <label for="horario_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-clock text-primary"></i> Horario <span class="text-danger">*</span>
                                </label>
                                <select name="horario_id" id="horario_id" class="form-select @error('horario_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar horario --</option>
                                    @foreach($horarios as $horario)
                                        <option value="{{ $horario->id }}" {{ old('horario_id', $cargaAcademica->horario_id) == $horario->id ? 'selected' : '' }}>
                                            @if(isset($horario->dias_agrupados) && count($horario->dias_agrupados) > 0)
                                                @foreach($horario->dias_agrupados as $dia)
                                                    {{ $dia }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            @else
                                                {{ $horario->dia_semana }}
                                            @endif
                                            | {{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fin, 0, 5) }} ({{ $horario->duracion_formateada }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('horario_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Seleccionar Aula --}}
                            <div class="mb-4">
                                <label for="aula_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-door-open text-primary"></i> Aula <span class="text-danger">*</span>
                                </label>
                                <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar aula --</option>
                                    @foreach($aulas as $aula)
                                        <option value="{{ $aula->id }}"
                                                {{ old('aula_id', $cargaAcademica->aula_id) == $aula->id ? 'selected' : '' }}>
                                            {{ $aula->codigo }} - {{ $aula->tipo }} (Cap: {{ $aula->capacidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('aula_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gestión y Periodo --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="gestion" class="form-label fw-bold">
                                        <i class="fa-solid fa-calendar text-primary"></i> Gestión <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="gestion" id="gestion"
                                           class="form-control @error('gestion') is-invalid @enderror"
                                           value="{{ old('gestion', $cargaAcademica->gestion) }}"
                                           min="2020" max="2099" required>
                                    @error('gestion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Año académico (ej: {{ date('Y') }})</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="periodo" class="form-label fw-bold">
                                        <i class="fa-solid fa-calendar-days text-primary"></i> Periodo <span class="text-danger">*</span>
                                    </label>
                                    <select name="periodo" id="periodo" class="form-select @error('periodo') is-invalid @enderror" required>
                                        <option value="">-- Seleccionar periodo --</option>
                                        <option value="1" {{ old('periodo', $cargaAcademica->periodo) == 1 ? 'selected' : '' }}>1° Semestre</option>
                                        <option value="2" {{ old('periodo', $cargaAcademica->periodo) == 2 ? 'selected' : '' }}>2° Semestre</option>
                                    </select>
                                    @error('periodo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
