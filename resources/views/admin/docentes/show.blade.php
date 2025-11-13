<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-eye text-info"></i> Detalles del Docente
            </h2>
            <a href="{{ route('admin.docentes.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Información Personal --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user"></i> Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-id-badge"></i> ID Docente
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">#{{ $docente->id }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-user-circle"></i> Nombre Completo
                                </label>
                                <p class="mb-0"><strong>{{ $docente->user->name }}</strong></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                </label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $docente->user->email }}">
                                        {{ $docente->user->email }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-phone"></i> Teléfono
                                </label>
                                <p class="mb-0">{{ $docente->user->telefono ?? 'No registrado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información Académica --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-graduation-cap"></i> Información Académica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">
                                        {{ $docente->cargaHoraria }} horas/semana
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-tag"></i> Categoría
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-primary fs-6">{{ $docente->categoria }}</span>
                                </p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="text-muted small">
                                    <i class="fa-solid fa-graduation-cap"></i> Profesión
                                </label>
                                <p class="mb-0">
                                    <strong>{{ $docente->profesion ?? 'No especificada' }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Materias Asignadas --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-book"></i> Materias Asignadas
                            </h5>
                            <span class="badge bg-light text-dark">{{ $docente->materias->count() }} materias</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($docente->materias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre de la Materia</th>
                                            <th>Nivel</th>
                                            <th class="text-center">Grupos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docente->materias as $materia)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $materia->codigo ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>{{ $materia->nombre }}</strong>
                                                </td>
                                                <td>{{ $materia->nivel ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">
                                                        {{ $materia->grupos->count() ?? 0 }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No tiene materias asignadas aún.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Historial de Asistencias --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-calendar-check"></i> Últimas Asistencias
                            </h5>
                            <span class="badge bg-dark">{{ $docente->asistencias->take(10)->count() }} registros</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($docente->asistencias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora Entrada</th>
                                            <th>Hora Salida</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docente->asistencias->take(10) as $asistencia)
                                            <tr>
                                                <td>{{ $asistencia->fecha ?? 'N/A' }}</td>
                                                <td>{{ $asistencia->horaEntrada ?? 'N/A' }}</td>
                                                <td>{{ $asistencia->horaSalida ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $asistencia->estado ?? 'Presente' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay registros de asistencia.</p>
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
                                <i class="fa-solid fa-book fa-2x text-success"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $docente->materias->count() }}</h3>
                                <small class="text-muted">Materias</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <i class="fa-solid fa-calendar-check fa-2x text-warning"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $docente->asistencias->count() }}</h3>
                                <small class="text-muted">Asistencias</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-clock fa-2x text-info"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0">{{ $docente->cargaHoraria }}</h3>
                                <small class="text-muted">Horas/Semana</small>
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
                            <a href="{{ route('admin.docentes.edit', $docente) }}"
                               class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i> Editar Docente
                            </a>

                            <a href="{{ route('admin.carga-academica.show', $docente) }}"
                               class="btn btn-info">
                                <i class="fa-solid fa-list-check"></i> Gestionar Carga Académica
                            </a>

                            <form action="{{ route('admin.docentes.destroy', $docente) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar este docente? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa-solid fa-trash"></i> Eliminar Docente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Información del Sistema --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-info-circle"></i> Información del Sistema
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <small class="text-muted">Registrado:</small><br>
                            <strong>{{ $docente->created_at->format('d/m/Y H:i') }}</strong>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">Última actualización:</small><br>
                            <strong>{{ $docente->updated_at->format('d/m/Y H:i') }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
