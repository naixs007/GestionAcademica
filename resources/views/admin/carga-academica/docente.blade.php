<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-user-graduate text-primary"></i> Carga Académica del Docente
            </h2>
            <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        {{-- Información del Docente --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-id-card"></i> Información del Docente
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="avatar-circle-xlarge bg-primary text-white mx-auto">
                            {{ substr($docente->user->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Nombre Completo:</small>
                                <h5 class="mb-0">{{ $docente->user->name }}</h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Email:</small>
                                <p class="mb-0">{{ $docente->user->email }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Categoría:</small>
                                <p class="mb-0"><span class="badge bg-info fs-6">{{ $docente->categoria }}</span></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Profesión:</small>
                                <p class="mb-0">{{ $docente->profesion ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Carga Horaria Máxima:</small>
                                <p class="mb-0"><strong>{{ number_format($docente->carga_maxima_horas ?? 24, 2) }} hrs/semana</strong></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted">Total de Asignaciones:</small>
                                <p class="mb-0"><strong>{{ $docente->cargasAcademicas->count() }} materia(s)</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cargas Académicas por Periodo --}}
        @forelse($estadisticas as $periodo => $datos)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-calendar-days"></i>
                            Gestión {{ $datos['gestion'] }} - Periodo {{ $datos['periodo'] }}
                        </h5>
                        <div>
                            <span class="badge bg-light text-dark me-2">
                                {{ $datos['total_materias'] }} Materia(s)
                            </span>
                            <span class="badge
                                @if($datos['porcentaje'] < 80) bg-success
                                @elseif($datos['porcentaje'] < 100) bg-warning
                                @else bg-danger
                                @endif">
                                {{ number_format($datos['total_horas'], 2) }} / {{ number_format($docente->carga_maxima_horas ?? 24, 2) }} hrs
                                ({{ number_format($datos['porcentaje'], 1) }}%)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa-solid fa-book"></i> Materia</th>
                                    <th><i class="fa-solid fa-users"></i> Grupo</th>
                                    <th><i class="fa-solid fa-clock"></i> Horario</th>
                                    <th><i class="fa-solid fa-door-open"></i> Aula</th>
                                    <th><i class="fa-solid fa-hourglass-half"></i> Carga Horaria</th>
                                    <th class="text-center"><i class="fa-solid fa-cogs"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datos['cargas'] as $carga)
                                    <tr>
                                        <td>
                                            <strong>{{ $carga->materia->sigla }}</strong> - {{ $carga->materia->nombre }}
                                            <br>
                                            <small class="text-muted">
                                                Código: {{ $carga->materia->codigo }} | Nivel: {{ $carga->materia->nivel }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info fs-6">{{ $carga->grupo->nombre }}</span>
                                            <br>
                                            <small class="text-muted">Cupo: {{ $carga->grupo->cupo_maximo }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $carga->horario->dia_semana }}</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ substr($carga->horario->hora_inicio, 0, 5) }} -
                                                {{ substr($carga->horario->hora_fin, 0, 5) }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $carga->aula->codigo }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $carga->aula->tipo }} | Cap: {{ $carga->aula->capacidad }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($carga->materia->cargaHoraria, 2) }} hrs</strong>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.carga-academica.edit', $carga) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Editar">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.carga-academica.destroy', $carga) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar esta asignación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td colspan="2">
                                        <strong class="text-success">
                                            {{ number_format($datos['total_horas'], 2) }} hrs/semana
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i>
                Este docente no tiene asignaciones de carga académica registradas.
            </div>
        @endforelse

        {{-- Resumen General --}}
        @if($estadisticas->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-chart-bar"></i> Resumen Histórico
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6 class="text-muted">Periodos Activos</h6>
                            <h3 class="text-primary">{{ $estadisticas->count() }}</h3>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Total Materias Asignadas</h6>
                            <h3 class="text-info">{{ $docente->cargasAcademicas->count() }}</h3>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Promedio de Carga</h6>
                            <h3 class="text-success">
                                {{ number_format($estadisticas->avg('total_horas'), 2) }} hrs/semana
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .avatar-circle-xlarge {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 3rem;
        }
    </style>
</x-admin-layout>
