<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-toggle-on me-2"></i>
                Habilitaciones de Asistencia
            </h2>
            <a href="{{ route('admin.habilitaciones.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nueva Habilitación
            </a>
        </div>

        {{-- Filtros --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.habilitaciones.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="filter_docente" class="form-label">Docente</label>
                        <select name="docente_id" id="filter_docente" class="form-select">
                            <option value="">Todos</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="filter_fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="filter_fecha" class="form-control" value="{{ request('fecha') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="filter_estado" class="form-label">Estado</label>
                        <select name="estado" id="filter_estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="Habilitada" {{ request('estado') == 'Habilitada' ? 'selected' : '' }}>Habilitada</option>
                            <option value="Utilizada" {{ request('estado') == 'Utilizada' ? 'selected' : '' }}>Utilizada</option>
                            <option value="Expirada" {{ request('estado') == 'Expirada' ? 'selected' : '' }}>Expirada</option>
                            <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabla de habilitaciones --}}
        <div class="card shadow-sm">
            <div class="card-body">
                @if($habilitaciones->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <p>No hay habilitaciones registradas</p>
                        <a href="{{ route('admin.habilitaciones.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Crear Primera Habilitación
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Docente</th>
                                    <th>Materia</th>
                                    <th>Grupo</th>
                                    <th>Horario</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($habilitaciones as $hab)
                                    <tr>
                                        <td>
                                            <i class="fa-solid fa-user-tie me-1"></i>
                                            {{ $hab->docente->user->name }}
                                        </td>
                                        <td>
                                            <strong>{{ $hab->cargaAcademica->materia->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $hab->cargaAcademica->materia->codigo }}</small>
                                        </td>
                                        <td>{{ $hab->cargaAcademica->grupo->nombre }}</td>
                                        <td>
                                            <small>
                                                {{ $hab->cargaAcademica->horario->dia_semana }}<br>
                                                {{ substr($hab->cargaAcademica->horario->hora_inicio, 0, 5) }} -
                                                {{ substr($hab->cargaAcademica->horario->hora_fin, 0, 5) }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($hab->fecha)->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($hab->fecha)->locale('es')->isoFormat('dddd') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($hab->estado === 'Habilitada')
                                                <span class="badge bg-success">
                                                    <i class="fa-solid fa-check-circle"></i> Habilitada
                                                </span>
                                            @elseif($hab->estado === 'Utilizada')
                                                <span class="badge bg-info">
                                                    <i class="fa-solid fa-check-double"></i> Utilizada
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $hab->fecha_utilizacion->format('d/m/Y H:i') }}
                                                </small>
                                            @elseif($hab->estado === 'Expirada')
                                                <span class="badge bg-secondary">
                                                    <i class="fa-solid fa-clock"></i> Expirada
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fa-solid fa-ban"></i> Cancelada
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.habilitaciones.show', $hab) }}"
                                                   class="btn btn-outline-info"
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($hab->estado === 'Habilitada')
                                                    <form action="{{ route('admin.habilitaciones.cancelar', $hab) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Cancelar esta habilitación?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-outline-warning" title="Cancelar">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($hab->estado !== 'Utilizada')
                                                    <form action="{{ route('admin.habilitaciones.destroy', $hab) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Eliminar esta habilitación?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center">
                        {{ $habilitaciones->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
