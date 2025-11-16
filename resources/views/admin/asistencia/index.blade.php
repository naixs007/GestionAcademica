<x-admin-layout>
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="fa-solid fa-clipboard-list"></i> Control de Asistencias de Docentes
            </h2>
            <a href="{{ route('admin.asistencia.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus-circle"></i> Registrar Asistencia
            </a>
        </div>

        {{-- Filtros --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-filter"></i> Filtros
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.asistencia.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="fecha_desde" class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"
                                   value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"
                                   value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="docente_id" class="form-label">Docente</label>
                            <select class="form-select" id="docente_id" name="docente_id">
                                <option value="">Todos</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos</option>
                                <option value="Presente" {{ request('estado') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                <option value="Ausente" {{ request('estado') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                                <option value="Justificado" {{ request('estado') == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                                <option value="Tardanza" {{ request('estado') == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.asistencia.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Limpiar
                        </a>
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
                                <th>Docente</th>
                                <th>Materia</th>
                                <th>Grupo</th>
                                <th>Horario</th>
                                <th>Estado</th>
                                <th>Hora Llegada</th>
                                <th class="d-none d-md-table-cell">Observaciones</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $asistencia)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $asistencia->docente->user->name }}</td>
                                    <td>{{ $asistencia->materia->nombre }}</td>
                                                                        <td>{{ $asistencia->grupo->nombre }}</td>
                                    <td>
                                        @if($asistencia->horario)
                                            {{ $asistencia->horario->dia_semana }}<br>
                                            <small class="text-muted">{{ $asistencia->horario->hora_inicio }} - {{ $asistencia->horario->hora_fin }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($asistencia->estado) {
                                                'Presente' => 'bg-success',
                                                'Ausente' => 'bg-danger',
                                                'Justificado' => 'bg-info',
                                                'Tardanza' => 'bg-warning text-dark',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $asistencia->estado }}</span>
                                    </td>
                                    <td>{{ $asistencia->hora_llegada ?? '-' }}</td>
                                    <td class="d-none d-md-table-cell">
                                        <small>{{ $asistencia->observaciones ? \Str::limit($asistencia->observaciones, 30) : '-' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.asistencia.show', $asistencia) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Ver">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.asistencia.edit', $asistencia) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Editar">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.asistencia.destroy', $asistencia) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar esta asistencia?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fs-1 d-block mb-2"></i>
                                        <p class="mb-0">No hay registros de asistencia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-3">
            {{ $asistencias->links() }}
        </div>
    </div>
</x-admin-layout>
