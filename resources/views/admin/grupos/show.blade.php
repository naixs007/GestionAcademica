<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-eye text-info"></i> Detalles del Grupo
            </h2>
            <a href="{{ route('admin.grupos.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Información del Grupo --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-users-rectangle"></i> Información del Grupo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-tag"></i> Nombre del Grupo
                                </label>
                                <p class="mb-0">
                                    <strong class="fs-5">{{ $grupo->nombre }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-users"></i> Capacidad Máxima
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">
                                        {{ $grupo->cupo_maximo }} estudiantes
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-calendar"></i> Fecha de Creación
                                </label>
                                <p class="mb-0">{{ $grupo->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Asignaciones de Carga Académica --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-calendar-check"></i> Asignaciones de Carga Académica
                            </h5>
                            <span class="badge bg-light text-dark">{{ $grupo->cargasAcademicas->count() }} asignaciones</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($grupo->cargasAcademicas && $grupo->cargasAcademicas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fa-solid fa-book"></i> Materia</th>
                                            <th><i class="fa-solid fa-chalkboard-user"></i> Docente</th>
                                            <th><i class="fa-solid fa-calendar"></i> Fecha Asignación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupo->cargasAcademicas as $carga)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $carga->materia->codigo }}</span>
                                                    <br>
                                                    <strong>{{ $carga->materia->nombre }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Nivel: {{ $carga->materia->nivel }} |
                                                        {{ $carga->materia->cargaHoraria }} hrs/semana
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-success text-white me-2">
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
                            <div class="text-center py-4">
                                <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">No hay asignaciones de carga académica para este grupo.</p>
                                <a href="{{ route('admin.carga-academica.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Asignar Materia y Docente
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Estadísticas del Grupo --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-chart-pie"></i> Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="fa-solid fa-users fa-2x text-info mb-2"></i>
                                    <h4 class="mb-0">{{ $grupo->cupo_maximo }}</h4>
                                    <small class="text-muted">Capacidad Máxima</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="fa-solid fa-user-check fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">0</h4>
                                    <small class="text-muted">Estudiantes Inscritos</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="fa-solid fa-user-plus fa-2x text-warning mb-2"></i>
                                    <h4 class="mb-0">{{ $grupo->cupo_maximo }}</h4>
                                    <small class="text-muted">Cupos Disponibles</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="col-lg-4">
                {{-- Tarjeta de Acciones --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-cog"></i> Acciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.grupos.edit', $grupo) }}"
                               class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i> Editar Grupo
                            </a>

                            <form action="{{ route('admin.grupos.destroy', $grupo) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar este grupo? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa-solid fa-trash"></i> Eliminar Grupo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Resumen Visual --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-info-circle"></i> Resumen
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>
                                <i class="fa-solid fa-tag text-primary"></i> Grupo:
                            </span>
                            <strong>{{ $grupo->nombre }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>
                                <i class="fa-solid fa-calendar-check text-warning"></i> Asignaciones:
                            </span>
                            <strong>{{ $grupo->cargasAcademicas->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>
                                <i class="fa-solid fa-users text-info"></i> Capacidad:
                            </span>
                            <strong>{{ $grupo->cupo_maximo }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa-solid fa-percentage text-success"></i> Ocupación:
                            </span>
                            <strong>0%</strong>
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
                            <small class="text-muted">Creado:</small><br>
                            <strong>{{ $grupo->created_at->format('d/m/Y H:i:s') }}</strong>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">Última actualización:</small><br>
                            <strong>{{ $grupo->updated_at->format('d/m/Y H:i:s') }}</strong>
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
