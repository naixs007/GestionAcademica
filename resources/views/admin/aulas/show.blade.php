<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-door-open"></i> Detalle del {{ ucfirst($aula->tipo) }}</h2>
            <div>
                <a href="{{ route('admin.aula.edit', $aula) }}" class="btn btn-warning">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.aula.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-info-circle"></i> Información del
                            {{ ucfirst($aula->tipo) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-signature"></i> Nombre:</strong>
                                <p class="mb-0 h4">{{ $aula->nombre }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-tags"></i> Tipo:</strong>
                                <p class="mb-0">
                                    @if ($aula->tipo === 'aula')
                                        <span class="badge bg-primary fs-6">
                                            <i class="fa-solid fa-chalkboard"></i> Aula
                                        </span>
                                    @else
                                        <span class="badge bg-info fs-6">
                                            <i class="fa-solid fa-flask"></i> Laboratorio
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-users"></i> Capacidad:</strong>
                                <p class="mb-0">{{ $aula->capacidad }} personas</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-book"></i> Materias Asignadas:</strong>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">{{ $aula->materias->count() }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-plus"></i> Fecha de Creación:</strong>
                                <p class="mb-0">{{ $aula->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-edit"></i> Última Actualización:</strong>
                                <p class="mb-0">{{ $aula->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($aula->materias->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-list"></i> Materias Asignadas</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Materia</th>
                                            <th>Nivel</th>
                                            <th>Carga Horaria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aula->materias as $materia)
                                            <tr>
                                                <td><strong>{{ $materia->codigo }}</strong></td>
                                                <td>{{ $materia->nombre }}</td>
                                                <td>
                                                    <span class="badge bg-info">Nivel {{ $materia->nivel }}</span>
                                                </td>
                                                <td>{{ $materia->cargaHoraria }}h</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mt-3">
                        <div class="card-body text-center text-muted">
                            <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                            <p>No hay materias asignadas a este {{ $aula->tipo }}</p>
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
                        <a href="{{ route('admin.aula.edit', $aula) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fa-solid fa-edit"></i> Editar {{ ucfirst($aula->tipo) }}
                        </a>
                        <form action="{{ route('admin.aula.destroy', $aula) }}" method="POST"
                            onsubmit="return confirm('¿Está seguro de eliminar este {{ $aula->tipo }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fa-solid fa-trash"></i> Eliminar {{ ucfirst($aula->tipo) }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fa-solid fa-chart-bar"></i> Estadísticas</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Tasa de Ocupación</small>
                            <div class="progress">
                                @php
                                    $ocupacion =
                                        $aula->materias->count() > 0 ? min(100, $aula->materias->count() * 10) : 0;
                                @endphp
                                <div class="progress-bar" role="progressbar" style="width: {{ $ocupacion }}%"
                                    aria-valuenow="{{ $ocupacion }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $ocupacion }}%
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-1">
                            <i class="fa-solid fa-book"></i> <strong>{{ $aula->materias->count() }}</strong> materias
                            asignadas
                        </p>
                        <p class="mb-0">
                            <i class="fa-solid fa-users"></i> Capacidad para <strong>{{ $aula->capacidad }}</strong>
                            personas
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
