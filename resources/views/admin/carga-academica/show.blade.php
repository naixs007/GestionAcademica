<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-eye text-info"></i> Detalle de Carga Académica
            </h2>
            <div>
                <a href="{{ route('admin.carga-academica.edit', $docente->id) }}" class="btn btn-warning">
                    <i class="fa-solid fa-edit"></i> Editar Carga
                </a>
                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Información del Docente --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-tie"></i> Información del Docente
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar-circle-large bg-primary text-white mb-3 mx-auto">
                            {{ substr($docente->user->name, 0, 2) }}
                        </div>
                        <h4 class="mb-1">{{ $docente->user->name }}</h4>
                        <p class="text-muted mb-3">{{ $docente->user->email }}</p>

                        <div class="row text-start">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Categoría:</small>
                                <p class="mb-0"><span class="badge bg-info">{{ $docente->categoria }}</span></p>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Profesión:</small>
                                <p class="mb-0">{{ $docente->profesion ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Carga Horaria Máxima:</small>
                                <p class="mb-0"><strong>{{ $docente->cargaHoraria }} horas/semana</strong></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Fecha de Registro:</small>
                                <p class="mb-0">{{ $docente->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estadísticas --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-chart-pie"></i> Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $materiasAsignadas = $docente->materias->count();
                            $cargaTotal = $docente->materias->sum('cargaHoraria');
                            $porcentajeCarga = $docente->cargaHoraria > 0 ? ($cargaTotal / $docente->cargaHoraria) * 100 : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Materias Asignadas</span>
                                <strong class="text-primary">{{ $materiasAsignadas }}</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Carga Horaria Total</span>
                                <strong class="text-success">{{ $cargaTotal }} hrs</strong>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Porcentaje de Carga</span>
                                <strong class="text-{{ $porcentajeCarga >= 100 ? 'danger' : ($porcentajeCarga >= 75 ? 'warning' : 'info') }}">
                                    {{ number_format($porcentajeCarga, 1) }}%
                                </strong>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $porcentajeCarga >= 100 ? 'danger' : ($porcentajeCarga >= 75 ? 'warning' : 'success') }}"
                                     role="progressbar"
                                     style="width: {{ min($porcentajeCarga, 100) }}%"
                                     aria-valuenow="{{ $porcentajeCarga }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ number_format($porcentajeCarga, 1) }}%
                                </div>
                            </div>
                        </div>

                        @if($porcentajeCarga >= 100)
                            <div class="alert alert-danger mt-3 mb-0">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <small>Carga horaria completa o excedida</small>
                            </div>
                        @elseif($porcentajeCarga >= 75)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fa-solid fa-clock"></i>
                                <small>Cerca del límite de carga horaria</small>
                            </div>
                        @elseif($porcentajeCarga > 0)
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fa-solid fa-check-circle"></i>
                                <small>Aún hay disponibilidad de horas</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Materias Asignadas --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-book-open"></i> Materias Asignadas
                            </h5>
                            <span class="badge bg-primary">{{ $docente->materias->count() }} materias</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($docente->materias as $materia)
                            <div class="border-bottom p-3 hover-bg-light">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-2" style="font-size: 1rem;">
                                                {{ $materia->codigo }}
                                            </span>
                                            <h5 class="mb-0">{{ $materia->nombre }}</h5>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-layer-group"></i> Nivel:
                                                </small>
                                                <p class="mb-1">{{ $materia->nivel }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-clock"></i> Carga Horaria:
                                                </small>
                                                <p class="mb-1"><strong>{{ $materia->cargaHoraria }} horas/semana</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fa-solid fa-users"></i> Grupos:
                                                </small>
                                                <p class="mb-1">
                                                    <span class="badge bg-info">{{ $materia->grupos->count() }} grupos</span>
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Lista de Grupos --}}
                                        @if($materia->grupos->count() > 0)
                                            <div class="mt-3">
                                                <small class="text-muted fw-bold">
                                                    <i class="fa-solid fa-users-rectangle"></i> Grupos Asociados:
                                                </small>
                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                    @foreach($materia->grupos as $grupo)
                                                        <span class="badge bg-secondary">
                                                            {{ $grupo->nombre }} (Cap: {{ $grupo->capacidad }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ms-3">
                                        <form action="{{ route('admin.carga-academica.destroy', $materia->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de remover esta materia del docente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Remover materia">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Este docente no tiene materias asignadas.</p>
                                <a href="{{ route('admin.carga-academica.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Asignar Materias
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Resumen de Horarios (si existen) --}}
                @if($docente->materias->count() > 0)
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-calendar-days"></i> Resumen de Horarios
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nivel</th>
                                            <th class="text-center">Horas/Semana</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docente->materias as $materia)
                                            <tr>
                                                <td>{{ $materia->nombre }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ $materia->codigo }}</span>
                                                </td>
                                                <td class="text-center">{{ $materia->nivel }}</td>
                                                <td class="text-center"><strong>{{ $materia->cargaHoraria }}</strong></td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-center">
                                                <strong class="text-primary">{{ $docente->materias->sum('cargaHoraria') }} hrs</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-circle-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2.5rem;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s;
        }
    </style>
</x-admin-layout>
