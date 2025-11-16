<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="fa-solid fa-eye me-2"></i>
                Detalle de Asistencia
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.asistencia.edit', $asistencia) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-pencil me-1"></i>
                    Editar
                </a>
                <a href="{{ route('admin.asistencia.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Información General --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Información General
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Fecha</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-calendar-days me-1"></i>
                                {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                <span class="text-muted">({{ \Carbon\Carbon::parse($asistencia->fecha)->isoFormat('dddd') }})</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Estado</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $asistencia->estadoBadgeColor }} fs-6">
                                    {{ $asistencia->estado }}
                                </span>
                            </p>
                        </div>

                        @if($asistencia->hora_llegada)
                        <div class="mb-3">
                            <label class="text-muted small">Hora de Llegada</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($asistencia->hora_llegada)->format('H:i') }}
                            </p>
                        </div>
                        @endif

                        @if($asistencia->observaciones)
                        <div>
                            <label class="text-muted small">Observaciones</label>
                            <p class="mb-0">{{ $asistencia->observaciones }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Información del Docente y Asignación --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa-solid fa-user-tie me-2"></i>
                            Docente y Asignación
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Docente --}}
                        <div class="mb-3">
                            <label class="text-muted small">Docente</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-user me-1"></i>
                                {{ $asistencia->docente->user->name ?? 'N/A' }}
                            </p>
                            @if($asistencia->docente->user->email)
                            <p class="mb-0 text-muted small">
                                <i class="fa-solid fa-envelope me-1"></i>
                                {{ $asistencia->docente->user->email }}
                            </p>
                            @endif
                        </div>

                        {{-- Materia --}}
                        @if($asistencia->materia)
                        <div class="mb-3">
                            <label class="text-muted small">Materia</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-book me-1"></i>
                                {{ $asistencia->materia->nombre }}
                            </p>
                            @if($asistencia->materia->codigo)
                            <p class="mb-0 text-muted small">
                                Código: {{ $asistencia->materia->codigo }}
                            </p>
                            @endif
                        </div>
                        @endif

                        {{-- Grupo --}}
                        @if($asistencia->grupo)
                        <div class="mb-3">
                            <label class="text-muted small">Grupo</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-users me-1"></i>
                                {{ $asistencia->grupo->nombre }}
                            </p>
                        </div>
                        @endif

                        {{-- Horario --}}
                        @if($asistencia->horario)
                        <div>
                            <label class="text-muted small">Horario</label>
                            <p class="mb-0 fw-semibold">
                                <i class="fa-solid fa-clock me-1"></i>
                                {{ $asistencia->horario->dia_semana }}
                            </p>
                            <p class="mb-0 text-muted">
                                {{ \Carbon\Carbon::parse($asistencia->horario->hora_inicio)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($asistencia->horario->hora_fin)->format('H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Metadatos --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>
                    Metadatos
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Creado el:</small>
                        <p class="mb-0">{{ $asistencia->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Última actualización:</small>
                        <p class="mb-0">{{ $asistencia->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('admin.asistencia.edit', $asistencia) }}" class="btn btn-primary">
                <i class="fa-solid fa-pencil me-1"></i>
                Editar
            </a>
            <form action="{{ route('admin.asistencia.destroy', $asistencia) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('¿Está seguro de eliminar este registro de asistencia?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash me-1"></i>
                    Eliminar
                </button>
            </form>
            <a href="{{ route('admin.asistencia.index') }}" class="btn btn-secondary">
                Volver al listado
            </a>
        </div>
    </div>
</x-admin-layout>
