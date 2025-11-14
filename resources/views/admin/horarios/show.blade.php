<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-clock"></i> Detalle del Horario</h2>
            <div>
                <a href="{{ route('admin.horario.edit', $horario) }}" class="btn btn-warning">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-info-circle"></i> Información del Horario</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-book"></i> Materia:</strong>
                                <p class="mb-0">{{ $horario->materia->nombre ?? 'N/A' }}</p>
                                <small class="text-muted">Código: {{ $horario->materia->codigo ?? 'N/A' }}</small>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-day"></i> Días de la Semana:</strong>
                                <p class="mb-0">
                                    @if (is_array($horario->diaSemana))
                                        @foreach ($horario->diaSemana as $dia)
                                            <span class="badge bg-primary me-1">{{ $dia }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-primary">{{ $horario->diaSemana }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong><i class="fa-solid fa-clock"></i> Hora de Inicio:</strong>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($horario->horaInicio)->format('H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fa-solid fa-clock"></i> Hora de Fin:</strong>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($horario->horaFin)->format('H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fa-solid fa-hourglass"></i> Duración:</strong>
                                <p class="mb-0">
                                    {{ \Carbon\Carbon::parse($horario->horaInicio)->diffInMinutes(\Carbon\Carbon::parse($horario->horaFin)) }}
                                    minutos
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-chalkboard"></i> Modalidad:</strong>
                                <p class="mb-0">
                                    @if ($horario->modalidad === 'presencial')
                                        <span class="badge bg-primary">
                                            <i class="fa-solid fa-building"></i> Presencial
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="fa-solid fa-laptop"></i> Virtual
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-check"></i> Asistencias Registradas:</strong>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ $horario->asistencias->count() }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-plus"></i> Fecha de Creación:</strong>
                                <p class="mb-0">{{ $horario->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-edit"></i> Última Actualización:</strong>
                                <p class="mb-0">{{ $horario->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($horario->asistencias->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-list"></i> Asistencias Registradas</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Docente</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($horario->asistencias->take(10) as $asistencia)
                                            <tr>
                                                <td>{{ $asistencia->fecha->format('d/m/Y') }}</td>
                                                <td>{{ $asistencia->docente->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $asistencia->estadoAsistencia === 'presente' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($asistencia->estadoAsistencia) }}
                                                    </span>
                                                </td>
                                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($horario->asistencias->count() > 10)
                                <p class="text-muted text-center mb-0">
                                    Mostrando 10 de {{ $horario->asistencias->count() }} asistencias registradas
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0"><i class="fa-solid fa-tools"></i> Acciones</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.horario.edit', $horario) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fa-solid fa-edit"></i> Editar Horario
                        </a>
                        <form action="{{ route('admin.horario.destroy', $horario) }}" method="POST"
                            onsubmit="return confirm('¿Está seguro de eliminar este horario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fa-solid fa-trash"></i> Eliminar Horario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
