<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-eye text-info"></i> Detalles de la Materia
            </h2>
            <a href="{{ route('admin.materia.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Información de la Materia --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book"></i> Información de la Materia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-barcode"></i> Código
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-primary fs-5">{{ $materia->codigo }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-id-badge"></i> ID Materia
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">#{{ $materia->id }}</span>
                                </p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-book-open"></i> Nombre de la Materia
                                </label>
                                <p class="mb-0">
                                    <strong class="fs-5">{{ $materia->nombre }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-layer-group"></i> Nivel / Semestre
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">{{ $materia->nivel_texto }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">
                                        {{ $materia->cargaHoraria }} horas/semana
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Asignaciones de Carga Académica --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-calendar-check"></i> Asignaciones de Carga Académica
                            </h5>
                            <span class="badge bg-light text-dark">{{ $materia->cargasAcademicas->count() }} asignaciones</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($materia->cargasAcademicas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fa-solid fa-chalkboard-user"></i> Docente</th>
                                            <th><i class="fa-solid fa-users-rectangle"></i> Grupo</th>
                                            <th><i class="fa-solid fa-calendar"></i> Fecha Asignación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materia->cargasAcademicas as $carga)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary text-white me-2">
                                                            {{ substr($carga->docente->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <strong>{{ $carga->docente->user->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <span class="badge bg-info">{{ $carga->docente->categoria }}</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($carga->grupo)
                                                        <span class="badge bg-primary">
                                                            {{ $carga->grupo->nombre }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">Capacidad: {{ $carga->grupo->capacidad }}</small>
                                                    @else
                                                        <span class="text-muted">Sin grupo específico</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $carga->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay asignaciones de carga académica para esta materia.</p>
                                <a href="{{ route('admin.carga-academica.create') }}" class="btn btn-primary mt-2">
                                    <i class="fa-solid fa-plus"></i> Asignar Docente
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Horarios --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-calendar-days"></i> Horarios
                            </h5>
                            <span class="badge bg-dark">{{ $materia->horarios->count() }} horarios</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($materia->horarios->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Aula</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materia->horarios as $horario)
                                            <tr>
                                                <td>{{ $horario->dia ?? 'N/A' }}</td>
                                                <td>{{ $horario->horaInicio ?? 'N/A' }}</td>
                                                <td>{{ $horario->horaFin ?? 'N/A' }}</td>
                                                <td>{{ $horario->aula->nombre ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay horarios configurados.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="col-lg-4">
                {{-- Estadísticas --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-chart-bar"></i> Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <i class="fa-solid fa-users-rectangle fa-2x text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $materia->grupos->count() }}</h3>
                                <small class="text-muted">Grupos</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <i class="fa-solid fa-calendar-days fa-2x text-warning"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $materia->horarios->count() }}</h3>
                                <small class="text-muted">Horarios</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-clock fa-2x text-info"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $materia->cargaHoraria }}</h3>
                                <small class="text-muted">Hrs/Semana</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-cog"></i> Acciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.materia.edit', $materia) }}"
                               class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i> Editar Materia
                            </a>

                            <form action="{{ route('admin.materia.destroy', $materia) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar esta materia? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger w-100"
                                        @if($materia->cargasAcademicas->count() > 0) disabled title="No se puede eliminar porque tiene asignaciones activas" @endif>
                                        <i class="fa-solid fa-trash"></i> Eliminar Materia
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- Información del Sistema --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-database"></i> Información del Sistema
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <small class="text-muted">Creada:</small><br>
                            <strong>{{ $materia->created_at->format('d/m/Y H:i:s') }}</strong>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">Última actualización:</small><br>
                            <strong>{{ $materia->updated_at->format('d/m/Y H:i:s') }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
        }
    </style>
</x-admin-layout>
