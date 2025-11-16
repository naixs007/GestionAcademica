<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-calendar-plus text-success"></i> Asignar Carga Académica
            </h2>
            <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-circle"></i> Error al asignar carga académica
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
            {{-- Formulario de asignación --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-wpforms"></i> Formulario de Asignación
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.carga-academica.store') }}" method="POST" id="formAsignarCarga">
                            @csrf

                            {{-- Seleccionar Docente --}}
                            <div class="mb-4">
                                <label for="docente_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-chalkboard-user text-primary"></i> Docente <span class="text-danger">*</span>
                                </label>
                                @forelse($docentes as $docente)
                                    @if($loop->first)
                                        <select name="docente_id" id="docente_id" class="form-select @error('docente_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar docente --</option>
                                    @endif
                                    <option value="{{ $docente->id }}"
                                            data-carga-actual="{{ number_format($docente->cargaHoraria, 2) }}"
                                            data-carga-maxima="{{ number_format($docente->carga_maxima_horas ?? 24, 2) }}"
                                            data-categoria="{{ $docente->categoria }}"
                                            {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->user->name }} - {{ $docente->categoria }}
                                        ({{ number_format($docente->cargaHoraria, 2) }}/{{ number_format($docente->carga_maxima_horas ?? 24, 2) }} hrs)
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay docentes registrados.
                                        <a href="{{ route('admin.docentes.create') }}" class="alert-link">Registrar docente</a>
                                    </div>
                                @endforelse
                                @error('docente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione el docente al que se le asignará la materia.
                                </small>
                            </div>

                            {{-- Información del Docente Seleccionado --}}
                            <div id="docenteInfo" class="alert alert-info d-none mb-4">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-user"></i> Información del Docente
                                </h6>
                                <p class="mb-1"><strong>Categoría:</strong> <span id="infoCategoria">-</span></p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-0"><strong>Carga Actual:</strong> <span id="infoCargaActual">-</span> hrs</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-0"><strong>Carga Máxima:</strong> <span id="infoCargaMaxima">-</span> hrs</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-0"><strong>Uso:</strong> <span id="infoPorcentaje" class="badge bg-info">-</span></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Seleccionar Materia --}}
                            <div class="mb-4">
                                <label for="materia_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-book text-primary"></i> Materia <span class="text-danger">*</span>
                                </label>
                                @forelse($materias as $materia)
                                    @if($loop->first)
                                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar materia --</option>
                                    @endif
                                    <option value="{{ $materia->id }}"
                                            data-carga="{{ number_format($materia->cargaHoraria, 2) }}"
                                            data-codigo="{{ $materia->codigo }}"
                                            data-nivel="{{ $materia->nivel }}"
                                            {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                        {{ $materia->codigo }} - {{ $materia->nombre }}
                                        ({{ number_format($materia->cargaHoraria, 2) }}h - Nivel {{ $materia->nivel }})
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay materias registradas.
                                        <a href="{{ route('admin.materia.create') }}" class="alert-link">Registrar materia</a>
                                    </div>
                                @endforelse
                                @error('materia_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione la materia que será impartida por el docente.
                                </small>
                            </div>

                            {{-- Información de la Materia Seleccionada --}}
                            <div id="materiaInfo" class="alert alert-warning d-none mb-4">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-book"></i> Información de la Materia
                                </h6>
                                <p class="mb-1"><strong>Código:</strong> <span id="infoCodigoMateria">-</span></p>
                                <p class="mb-1"><strong>Nivel:</strong> <span id="infoNivelMateria">-</span></p>
                                <p class="mb-0"><strong>Carga Horaria:</strong> <span id="infoCargaMateria">-</span> hrs/semana</p>
                            </div>

                            {{-- Seleccionar Grupo --}}
                            <div class="mb-4">
                                <label for="grupo_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-users-rectangle text-primary"></i> Grupo <span class="text-danger">*</span>
                                </label>
                                @forelse($grupos as $grupo)
                                    @if($loop->first)
                                        <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar grupo --</option>
                                    @endif
                                    <option value="{{ $grupo->id }}"
                                            data-cupo="{{ $grupo->cupo_maximo }}"
                                            {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                        {{ $grupo->nombre }} - Cupo: {{ $grupo->cupo_maximo }} estudiantes
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay grupos registrados.
                                        <a href="{{ route('admin.grupo.create') }}" class="alert-link">Registrar grupo</a>
                                    </div>
                                @endforelse
                                @error('grupo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione el grupo al que se asignará la materia.
                                </small>
                            </div>

                            {{-- Seleccionar Horario --}}
                            <div class="mb-4">
                                <label for="horario_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-clock text-primary"></i> Horario <span class="text-danger">*</span>
                                </label>
                                @forelse($horarios as $horario)
                                    @if($loop->first)
                                        <select name="horario_id" id="horario_id" class="form-select @error('horario_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar horario --</option>
                                    @endif
                                    <option value="{{ $horario->id }}" {{ old('horario_id') == $horario->id ? 'selected' : '' }}>
                                        @if(isset($horario->dias_agrupados) && count($horario->dias_agrupados) > 0)
                                            @foreach($horario->dias_agrupados as $dia)
                                                {{ $dia }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        @else
                                            {{ $horario->dia_semana }}
                                        @endif
                                        | {{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fin, 0, 5) }} ({{ $horario->duracion_formateada }})
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay horarios registrados.
                                        <a href="{{ route('admin.horario.create') }}" class="alert-link">Registrar horario</a>
                                    </div>
                                @endforelse
                                @error('horario_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione el bloque horario en que se impartirá la clase.
                                </small>
                            </div>

                            {{-- Seleccionar Aula --}}
                            <div class="mb-4">
                                <label for="aula_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-door-open text-primary"></i> Aula <span class="text-danger">*</span>
                                </label>
                                @forelse($aulas as $aula)
                                    @if($loop->first)
                                        <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar aula --</option>
                                    @endif
                                    <option value="{{ $aula->id }}"
                                            data-tipo="{{ $aula->tipo }}"
                                            data-capacidad="{{ $aula->capacidad }}"
                                            {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->codigo }} - {{ $aula->tipo }} (Cap: {{ $aula->capacidad }})
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay aulas registradas.
                                        <a href="{{ route('admin.aula.create') }}" class="alert-link">Registrar aula</a>
                                    </div>
                                @endforelse
                                @error('aula_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione el aula donde se impartirá la clase.
                                </small>
                            </div>

                            {{-- Gestión y Periodo --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="gestion" class="form-label fw-bold">
                                        <i class="fa-solid fa-calendar text-primary"></i> Gestión <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="gestion" id="gestion"
                                           class="form-control @error('gestion') is-invalid @enderror"
                                           value="{{ old('gestion', date('Y')) }}"
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
                                        <option value="">-- Seleccionar --</option>
                                        <option value="1" {{ old('periodo') == '1' ? 'selected' : '' }}>Periodo 1</option>
                                        <option value="2" {{ old('periodo') == '2' ? 'selected' : '' }}>Periodo 2</option>
                                    </select>
                                    @error('periodo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Periodo académico (1 o 2)</small>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success" id="btnSubmit">
                                    <i class="fa-solid fa-save"></i> Asignar Carga Académica
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Alertas de conflictos --}}
                <div id="alertasConflictos" class="mt-3">
                    <!-- Las alertas se insertarán aquí dinámicamente -->
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="col-lg-4">
                {{-- Card de ayuda --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-lightbulb"></i> Instrucciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0 ps-3">
                            <li class="mb-2">Seleccione el <strong>docente</strong> que impartirá la materia.</li>
                            <li class="mb-2">Elija la <strong>materia</strong> a asignar.</li>
                            <li class="mb-2">Seleccione el <strong>grupo</strong> de estudiantes.</li>
                            <li class="mb-2">Defina el <strong>horario</strong> de la clase.</li>
                            <li class="mb-2">Asigne el <strong>aula</strong> donde se impartirá.</li>
                            <li>Complete la <strong>gestión</strong> y <strong>periodo</strong> académico.</li>
                        </ol>
                    </div>
                </div>

                {{-- Card de estadísticas --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-chart-bar"></i> Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Docentes Registrados</label>
                            <h4 class="mb-0 text-primary">{{ $docentes->count() }}</h4>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Materias Disponibles</label>
                            <h4 class="mb-0 text-success">{{ $materias->count() }}</h4>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Grupos Registrados</label>
                            <h4 class="mb-0 text-info">{{ $grupos->count() }}</h4>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Horarios Disponibles</label>
                            <h4 class="mb-0 text-warning">{{ $horarios->count() }}</h4>
                        </div>
                        <div>
                            <label class="text-muted small">Aulas Disponibles</label>
                            <h4 class="mb-0 text-secondary">{{ $aulas->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const docenteSelect = document.getElementById('docente_id');
            const materiaSelect = document.getElementById('materia_id');
            const grupoSelect = document.getElementById('grupo_id');
            const horarioSelect = document.getElementById('horario_id');
            const aulaSelect = document.getElementById('aula_id');
            const gestionInput = document.getElementById('gestion');
            const periodoSelect = document.getElementById('periodo');
            const docenteInfo = document.getElementById('docenteInfo');
            const materiaInfo = document.getElementById('materiaInfo');
            const btnSubmit = document.getElementById('btnSubmit');
            const alertasContainer = document.getElementById('alertasConflictos');

            // Función para actualizar carga horaria del docente según gestión/periodo
            function actualizarCargaDocente() {
                if(!docenteSelect || !docenteSelect.value) return;
                if(!gestionInput || !gestionInput.value) return;
                if(!periodoSelect || !periodoSelect.value) return;

                const docenteId = docenteSelect.value;
                const gestion = gestionInput.value;
                const periodo = periodoSelect.value;
                const option = docenteSelect.options[docenteSelect.selectedIndex];
                const cargaMaxima = parseFloat(option.dataset.cargaMaxima);

                // Llamada AJAX para obtener carga actualizada
                fetch(`{{ url('admin/carga-academica/api/docente') }}/${docenteId}/carga?gestion=${gestion}&periodo=${periodo}`)
                    .then(response => response.json())
                    .then(data => {
                        const cargaActual = data.cargaActual;
                        const porcentaje = data.porcentaje;

                        document.getElementById('infoCargaActual').textContent = cargaActual.toFixed(2);
                        document.getElementById('infoCargaMaxima').textContent = cargaMaxima.toFixed(2);

                        const badgePorcentaje = document.getElementById('infoPorcentaje');
                        badgePorcentaje.textContent = porcentaje.toFixed(2) + '%';

                        // Cambiar color del badge según el porcentaje
                        badgePorcentaje.className = 'badge';
                        if(porcentaje < 80) {
                            badgePorcentaje.classList.add('bg-success');
                        } else if(porcentaje < 100) {
                            badgePorcentaje.classList.add('bg-warning');
                        } else {
                            badgePorcentaje.classList.add('bg-danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener carga del docente:', error);
                    });
            }

            // Mostrar información del docente seleccionado
            if(docenteSelect) {
                docenteSelect.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if(this.value) {
                        document.getElementById('infoCategoria').textContent = option.dataset.categoria;
                        docenteInfo.classList.remove('d-none');
                        actualizarCargaDocente(); // Actualizar con periodo/gestión actual
                    } else {
                        docenteInfo.classList.add('d-none');
                    }
                    verificarConflictos();
                });
            }

            // Actualizar carga cuando cambien gestión o periodo
            if(gestionInput) {
                gestionInput.addEventListener('change', actualizarCargaDocente);
                gestionInput.addEventListener('input', actualizarCargaDocente);
            }
            if(periodoSelect) {
                periodoSelect.addEventListener('change', actualizarCargaDocente);
            }

            // Mostrar información de la materia seleccionada
            if(materiaSelect) {
                materiaSelect.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if(this.value) {
                        document.getElementById('infoCodigoMateria').textContent = option.dataset.codigo;
                        document.getElementById('infoNivelMateria').textContent = option.dataset.nivel;
                        document.getElementById('infoCargaMateria').textContent = option.dataset.carga;
                        materiaInfo.classList.remove('d-none');
                    } else {
                        materiaInfo.classList.add('d-none');
                    }
                });
            }

            // Verificar conflictos cuando cambien los campos relevantes
            if(grupoSelect) grupoSelect.addEventListener('change', verificarConflictos);
            if(horarioSelect) horarioSelect.addEventListener('change', verificarConflictos);
            if(aulaSelect) aulaSelect.addEventListener('change', verificarConflictos);
            if(gestionInput) gestionInput.addEventListener('change', verificarConflictos);
            if(periodoSelect) periodoSelect.addEventListener('change', verificarConflictos);

            function verificarConflictos() {
                // Validar que los campos necesarios estén completos
                const horarioId = horarioSelect?.value;
                const gestion = gestionInput?.value;
                const periodo = periodoSelect?.value;

                if (!horarioId || !gestion || !periodo) {
                    alertasContainer.innerHTML = '';
                    return;
                }

                // Preparar datos para enviar
                const data = {
                    horario_id: horarioId,
                    gestion: gestion,
                    periodo: periodo,
                    docente_id: docenteSelect?.value || null,
                    aula_id: aulaSelect?.value || null,
                    grupo_id: grupoSelect?.value || null
                };

                // Hacer petición AJAX
                fetch('{{ route('admin.carga-academica.verificar-conflictos') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    mostrarAlertas(result.conflictos);
                })
                .catch(error => {
                    console.error('Error al verificar conflictos:', error);
                });
            }

            function mostrarAlertas(conflictos) {
                alertasContainer.innerHTML = '';

                if (conflictos.length === 0) {
                    btnSubmit.disabled = false;
                    return;
                }

                // Mostrar cada conflicto como una alerta
                conflictos.forEach(conflicto => {
                    const icono = conflicto.tipo === 'docente' ? 'fa-chalkboard-user' :
                                 conflicto.tipo === 'aula' ? 'fa-door-open' : 'fa-users-rectangle';

                    const alerta = document.createElement('div');
                    alerta.className = 'alert alert-danger alert-dismissible fade show';
                    alerta.innerHTML = `
                        <h6 class="alert-heading">
                            <i class="fa-solid ${icono}"></i>
                            Conflicto detectado: ${conflicto.tipo.charAt(0).toUpperCase() + conflicto.tipo.slice(1)}
                        </h6>
                        <p class="mb-0">${conflicto.mensaje}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    alertasContainer.appendChild(alerta);
                });

                // Deshabilitar botón de envío si hay conflictos
                btnSubmit.disabled = true;

                // Scroll suave hacia las alertas
                alertasContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    </script>
</x-admin-layout>
