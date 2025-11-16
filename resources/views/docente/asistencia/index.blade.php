@extends('layouts.docente')

@section('title', 'Mi Asistencia')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fa-solid fa-clipboard-list"></i> Mi Historial de Asistencia
        </h2>
        <a href="{{ route('docente.asistencia.marcar') }}" class="btn btn-primary">
            <i class="fa-solid fa-hand-pointer"></i> Marcar Asistencia
        </a>
    </div>

    {{-- Información del Docente --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $docente->user->name }}</h5>
            <p class="mb-0 text-muted">Docente</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-filter"></i> Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('docente.asistencia.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="Presente" {{ request('estado') == 'Presente' ? 'selected' : '' }}>Presente</option>
                            <option value="Ausente" {{ request('estado') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                            <option value="Justificado" {{ request('estado') == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                            <option value="Tardanza" {{ request('estado') == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa-solid fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('docente.asistencia.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Asistencias --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th>Hora Llegada</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asistencias as $asistencia)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $asistencia->materia->nombre }}</td>
                                <td>{{ $asistencia->grupo->nombre }}</td>
                                <td>{{ $asistencia->horario->descripcion }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($asistencia->estado) {
                                            'Presente' => 'bg-success',
                                            'Ausente' => 'bg-danger',
                                            'Justificado' => 'bg-info',
                                            'Tardanza' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $asistencia->estado }}</span>
                                </td>
                                <td>{{ $asistencia->hora_llegada ?? '-' }}</td>
                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fs-1 d-block mb-2"></i>
                                    No hay registros de asistencia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $asistencias->links() }}
    </div>
</div>
@endsection
