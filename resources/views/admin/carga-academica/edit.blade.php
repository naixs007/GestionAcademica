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
            {{-- Información del Docente --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-tie"></i> Docente
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar-circle-large bg-primary text-white mb-3 mx-auto">
                            {{ substr($docente->user->name, 0, 2) }}
                        </div>
                        <h4 class="mb-1">{{ $docente->user->name }}</h4>
                        <p class="text-muted mb-3">{{ $docente->user->email }}</p>

                        <div class="row text-start">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Categoría:</small>
                                <p class="mb-0"><span class="badge bg-info">{{ $docente->categoria }}</span></p>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Carga Horaria Máxima:</small>
                                <p class="mb-0"><strong>{{ $docente->cargaHoraria }} horas/semana</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estadísticas Actuales --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-chart-bar"></i> Estado Actual
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $cargasActuales = $docente->cargasAcademicas->count();
                            $materiasActuales = $docente->materias->count();
                            $cargaActual = $docente->materias->sum('cargaHoraria');
                            $porcentajeActual = $docente->cargaHoraria > 0 ? ($cargaActual / $docente->cargaHoraria) * 100 : 0;
                        @endphp

                        <div class="mb-3">
                            <label class="text-muted small">Asignaciones</label>
                            <h4 class="mb-0 text-primary">{{ $cargasActuales }}</h4>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Materias Únicas</label>
                            <h4 class="mb-0 text-info">{{ $materiasActuales }}</h4>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Carga Horaria Actual</label>
                            <h4 class="mb-0 text-success">{{ $cargaActual }} / {{ $docente->cargaHoraria }} hrs</h4>
                        </div>

                        <div>
                            <label class="text-muted small">Porcentaje de Carga</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-{{ $porcentajeActual >= 100 ? 'danger' : ($porcentajeActual >= 75 ? 'warning' : 'success') }}"
                                     role="progressbar"
                                     style="width: {{ min($porcentajeActual, 100) }}%">
                                    {{ number_format($porcentajeActual, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de Edición --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book-open"></i> Gestionar Materias Asignadas
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.carga-academica.update', $docente->id) }}" method="POST" id="formEditarCarga">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle"></i>
                                <strong>Instrucciones:</strong> Seleccione las materias y grupos que desea asignar a este docente.
                                Las asignaciones actuales no seleccionadas serán eliminadas.
                            </div>

                            {{-- Lista de materias disponibles --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fa-solid fa-book text-primary"></i> Materias Disponibles <span class="text-danger">*</span>
                                </label>

                                @if($materias->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50" class="text-center">
                                                        <input type="checkbox" id="selectAll" title="Seleccionar todas">
                                                    </th>
                                                    <th>Código</th>
                                                    <th>Nombre de la Materia</th>
                                                    <th>Nivel</th>
                                                    <th class="text-center">Horas/Semana</th>
                                                    <th class="text-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($materias as $materia)
                                                    @php
                                                        $esAsignada = $docente->materias->contains($materia->id);
                                                    @endphp
                                                    <tr class="{{ $esAsignada ? 'table-success' : '' }}">
                                                        <td class="text-center">
                                                            <input type="checkbox"
                                                                   name="materias[]"
                                                                   value="{{ $materia->id }}"
                                                                   class="form-check-input materia-checkbox"
                                                                   data-carga="{{ $materia->cargaHoraria }}"
                                                                   {{ $esAsignada ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $materia->codigo }}</span>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $materia->nombre }}</strong>
                                                        </td>
                                                        <td>{{ $materia->nivel }}</td>
                                                        <td class="text-center"><strong>{{ $materia->cargaHoraria }}</strong></td>
                                                        <td class="text-center">
                                                            @if($esAsignada)
                                                                <span class="badge bg-success">
                                                                    <i class="fa-solid fa-check"></i> Asignada
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    <i class="fa-solid fa-circle"></i> Disponible
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>                                    {{-- Resumen de carga seleccionada --}}
                                    <div class="alert alert-primary mt-3" id="resumenCarga">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Materias Seleccionadas:</strong>
                                                <span id="materiasCount">{{ $docente->materias->count() }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Carga Total:</strong>
                                                <span id="cargaTotal">{{ $docente->materias->sum('cargaHoraria') }}</span> hrs
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Disponible:</strong>
                                                <span id="cargaDisponible">{{ $docente->cargaHoraria - $docente->materias->sum('cargaHoraria') }}</span> hrs
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 20px;">
                                            <div class="progress-bar"
                                                 id="progressBar"
                                                 role="progressbar"
                                                 style="width: {{ min($porcentajeActual, 100) }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning" id="alertaExceso" style="display: none;">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        <strong>¡Atención!</strong> La carga horaria seleccionada excede el límite del docente.
                                    </div>

                                @else
                                    <div class="alert alert-warning">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        No hay materias disponibles para asignar.
                                        <a href="{{ route('admin.materia.create') }}" class="alert-link">Registrar nueva materia</a>
                                    </div>
                                @endif
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                                @if($materias->count() > 0)
                                    <button type="submit" class="btn btn-success" id="btnSubmit">
                                        <i class="fa-solid fa-save"></i> Guardar Cambios
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Materias actualmente asignadas --}}
                @if($docente->cargasAcademicas->count() > 0)
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-check-circle"></i> Asignaciones Actuales de Carga Académica
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Grupo</th>
                                            <th>Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docente->cargasAcademicas as $carga)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $carga->materia->codigo }}</span>
                                                    <br>
                                                    <small>{{ $carga->materia->nombre }}</small>
                                                </td>
                                                <td>
                                                    @if($carga->grupo)
                                                        <span class="badge bg-info">{{ $carga->grupo->nombre }}</span>
                                                    @else
                                                        <span class="text-muted">Sin grupo</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $carga->materia->cargaHoraria }}</strong> hrs</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.materia-checkbox');
            const selectAll = document.getElementById('selectAll');
            const materiasCount = document.getElementById('materiasCount');
            const cargaTotal = document.getElementById('cargaTotal');
            const cargaDisponible = document.getElementById('cargaDisponible');
            const progressBar = document.getElementById('progressBar');
            const alertaExceso = document.getElementById('alertaExceso');
            const cargaMaxima = {{ $docente->cargaHoraria }};

            // Función para actualizar el resumen
            function actualizarResumen() {
                let count = 0;
                let totalHoras = 0;

                checkboxes.forEach(checkbox => {
                    if(checkbox.checked) {
                        count++;
                        totalHoras += parseInt(checkbox.dataset.carga);
                    }
                });

                const disponible = cargaMaxima - totalHoras;
                const porcentaje = (totalHoras / cargaMaxima) * 100;

                materiasCount.textContent = count;
                cargaTotal.textContent = totalHoras;
                cargaDisponible.textContent = disponible;

                // Actualizar barra de progreso
                progressBar.style.width = Math.min(porcentaje, 100) + '%';

                if(porcentaje >= 100) {
                    progressBar.classList.remove('bg-success', 'bg-warning');
                    progressBar.classList.add('bg-danger');
                    alertaExceso.style.display = 'block';
                } else if(porcentaje >= 75) {
                    progressBar.classList.remove('bg-success', 'bg-danger');
                    progressBar.classList.add('bg-warning');
                    alertaExceso.style.display = 'none';
                } else {
                    progressBar.classList.remove('bg-warning', 'bg-danger');
                    progressBar.classList.add('bg-success');
                    alertaExceso.style.display = 'none';
                }

                progressBar.textContent = porcentaje.toFixed(1) + '%';
            }

            // Evento para cada checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    actualizarResumen();
                });
            });

            // Seleccionar/Deseleccionar todas
            if(selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    actualizarResumen();
                });
            }

            // Actualizar al cargar la página
            actualizarResumen();
        });
    </script>

    <style>
        .avatar-circle-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2.5rem;
        }

        .table-success {
            background-color: #d1f2eb !important;
        }
    </style>
</x-admin-layout>
