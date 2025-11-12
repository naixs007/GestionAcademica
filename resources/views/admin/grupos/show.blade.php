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
                                    <i class="fa-solid fa-id-badge"></i> ID Grupo
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">#{{ $grupo->id }}</span>
                                </p>
                            </div>
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
                                        {{ $grupo->capacidad }} estudiantes
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

                {{-- Información de la Materia --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book"></i> Materia Asignada
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($grupo->materias)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-barcode"></i> Código
                                    </label>
                                    <p class="mb-0">
                                        <span class="badge bg-primary">{{ $grupo->materias->codigo ?? 'N/A' }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-book-open"></i> Nombre de la Materia
                                    </label>
                                    <p class="mb-0">
                                        <strong>{{ $grupo->materias->nombre }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-layer-group"></i> Nivel
                                    </label>
                                    <p class="mb-0">{{ $grupo->materias->nivel ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-clock"></i> Horas Semanales
                                    </label>
                                    <p class="mb-0">{{ $grupo->materias->horasSemanales ?? 'N/A' }} horas</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fa-solid fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <p class="text-muted mb-0">No hay materia asignada a este grupo.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Información del Docente --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-chalkboard-user"></i> Docente Responsable
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($grupo->materias && $grupo->materias->docente)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-user-circle"></i> Nombre Completo
                                    </label>
                                    <p class="mb-0">
                                        <strong>{{ $grupo->materias->docente->user->name }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                    </label>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $grupo->materias->docente->user->email }}">
                                            {{ $grupo->materias->docente->user->email }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-tag"></i> Categoría
                                    </label>
                                    <p class="mb-0">
                                        <span class="badge bg-primary">
                                            {{ $grupo->materias->docente->categoria }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">
                                        <i class="fa-solid fa-graduation-cap"></i> Profesión
                                    </label>
                                    <p class="mb-0">
                                        {{ $grupo->materias->docente->profesion ?? 'No especificada' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fa-solid fa-user-slash fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay docente asignado a la materia de este grupo.</p>
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
                                    <h4 class="mb-0">{{ $grupo->capacidad }}</h4>
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
                                    <h4 class="mb-0">{{ $grupo->capacidad }}</h4>
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
                            @can('grupos.editar')
                                <a href="{{ route('admin.grupos.edit', $grupo) }}" 
                                   class="btn btn-warning">
                                    <i class="fa-solid fa-edit"></i> Editar Grupo
                                </a>
                            @endcan

                            @can('grupos.eliminar')
                                <form action="{{ route('admin.grupos.destroy', $grupo) }}" 
                                      method="POST"
                                      onsubmit="return confirm('¿Está seguro de eliminar este grupo? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fa-solid fa-trash"></i> Eliminar Grupo
                                    </button>
                                </form>
                            @endcan
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
                                <i class="fa-solid fa-book text-warning"></i> Materia:
                            </span>
                            <strong>{{ $grupo->materias->codigo ?? 'N/A' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>
                                <i class="fa-solid fa-users text-info"></i> Capacidad:
                            </span>
                            <strong>{{ $grupo->capacidad }}</strong>
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
</x-admin-layout>
