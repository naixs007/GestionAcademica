<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Detalles de Habilitación
            </h2>
            <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header {{ $habilitacion->estado === 'Habilitada' ? 'bg-success' : ($habilitacion->estado === 'Utilizada' ? 'bg-info' : 'bg-secondary') }} text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-toggle-on"></i> Habilitación #{{ $habilitacion->id }}
                    <span class="badge bg-light text-dark ms-2">{{ $habilitacion->estado }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3"><i class="fa-solid fa-user-tie"></i> Información del Docente</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Nombre:</th>
                                <td><strong>{{ $habilitacion->docente->user->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $habilitacion->docente->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Especialidad:</th>
                                <td>{{ $habilitacion->docente->especialidad ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3"><i class="fa-solid fa-book"></i> Información de la Clase</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Materia:</th>
                                <td><strong>{{ $habilitacion->cargaAcademica->materia->nombre }}</strong></td>
                            </tr>
                            <tr>
                                <th>Código:</th>
                                <td>{{ $habilitacion->cargaAcademica->materia->codigo }}</td>
                            </tr>
                            <tr>
                                <th>Grupo:</th>
                                <td>{{ $habilitacion->cargaAcademica->grupo->nombre }}</td>
                            </tr>
                            <tr>
                                <th>Horario:</th>
                                <td>
                                    {{ $habilitacion->cargaAcademica->horario->dia_semana }}<br>
                                    {{ substr($habilitacion->cargaAcademica->horario->hora_inicio, 0, 5) }} -
                                    {{ substr($habilitacion->cargaAcademica->horario->hora_fin, 0, 5) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Aula:</th>
                                <td>{{ $habilitacion->cargaAcademica->aula->nombre ?? 'Sin aula' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-3"><i class="fa-solid fa-calendar"></i> Información de la Habilitación</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Fecha:</th>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($habilitacion->fecha)->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($habilitacion->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    @if($habilitacion->estado === 'Habilitada')
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-check-circle"></i> Habilitada
                                        </span>
                                    @elseif($habilitacion->estado === 'Utilizada')
                                        <span class="badge bg-info">
                                            <i class="fa-solid fa-check-double"></i> Utilizada
                                        </span>
                                    @elseif($habilitacion->estado === 'Expirada')
                                        <span class="badge bg-secondary">
                                            <i class="fa-solid fa-clock"></i> Expirada
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fa-solid fa-ban"></i> Cancelada
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if($habilitacion->estado === 'Utilizada' && $habilitacion->fecha_utilizacion)
                                <tr>
                                    <th>Utilizada el:</th>
                                    <td>
                                        {{ $habilitacion->fecha_utilizacion->format('d/m/Y H:i:s') }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Creada por:</th>
                                <td>{{ $habilitacion->creador->name ?? 'Sistema' }}</td>
                            </tr>
                            <tr>
                                <th>Fecha de creación:</th>
                                <td>{{ $habilitacion->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-3"><i class="fa-solid fa-comment"></i> Observaciones</h6>
                        @if($habilitacion->observaciones)
                            <div class="alert alert-light">
                                {{ $habilitacion->observaciones }}
                            </div>
                        @else
                            <p class="text-muted fst-italic">Sin observaciones</p>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    @if($habilitacion->estado === 'Habilitada')
                        <form action="{{ route('admin.habilitaciones.cancelar', $habilitacion) }}"
                              method="POST"
                              onsubmit="return confirm('¿Está seguro de cancelar esta habilitación?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar Habilitación
                            </button>
                        </form>
                    @endif

                    @if($habilitacion->estado !== 'Utilizada')
                        <form action="{{ route('admin.habilitaciones.destroy', $habilitacion) }}"
                              method="POST"
                              onsubmit="return confirm('¿Está seguro de eliminar esta habilitación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>
                                Eliminar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
