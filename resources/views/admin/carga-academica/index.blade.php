<x-admin-layout>
    <div class="container-fluid py-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-list-check"></i> Asignar Carga Académica</h2>
        <a href="{{ route('admin.carga-academica.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nueva Asignación
        </a>
    </div>

        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tarjeta con tabla de docentes --}}
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list"></i> Carga Académica por Docente
                    </h5>
                    <span class="badge bg-info">{{ $docentes->total() }} docentes</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-user"></i> Docente
                                </th>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-tag"></i> Categoría
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-book"></i> Materias Asignadas
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-chart-line"></i> Estado
                                </th>
                                <th class="text-center sticky-col" style="min-width: 150px;">
                                    <i class="fa-solid fa-cogs"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($docentes as $docente)
                                @php
                                    $materiasAsignadas = $docente->materias->count();
                                    $cargaTotal = $docente->materias->sum('cargaHoraria');
                                    $porcentajeCarga = $docente->cargaHoraria > 0 ? ($cargaTotal / $docente->cargaHoraria) * 100 : 0;

                                    if($porcentajeCarga >= 100) {
                                        $estadoClass = 'danger';
                                        $estadoIcon = 'exclamation-triangle';
                                        $estadoTexto = 'Completo';
                                    } elseif($porcentajeCarga >= 75) {
                                        $estadoClass = 'warning';
                                        $estadoIcon = 'clock';
                                        $estadoTexto = 'Casi Completo';
                                    } elseif($porcentajeCarga > 0) {
                                        $estadoClass = 'info';
                                        $estadoIcon = 'check-circle';
                                        $estadoTexto = 'Disponible';
                                    } else {
                                        $estadoClass = 'secondary';
                                        $estadoIcon = 'circle';
                                        $estadoTexto = 'Sin Asignar';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($docente->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $docente->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $docente->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $docente->categoria }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">{{ $materiasAsignadas }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $cargaTotal }}</strong> / {{ $docente->cargaHoraria }} hrs
                                        <div class="progress mt-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $estadoClass }}"
                                                 role="progressbar"
                                                 style="width: {{ min($porcentajeCarga, 100) }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $estadoClass }}">
                                            <i class="fa-solid fa-{{ $estadoIcon }}"></i> {{ $estadoTexto }}
                                        </span>
                                    </td>
                                    <td class="text-center sticky-col">
                                                                                <div class="btn-group" role="group">
                                            <a href="{{ route('admin.carga-academica.show', $docente) }}"
                                               class="btn btn-sm btn-info"
                                               title="Ver detalles">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.carga-academica.edit', $docente) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay docentes registrados.</p>
                                        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Registrar Docente
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($docentes->hasPages())
                <div class="card-footer">
                    {{ $docentes->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
        }

        .sticky-col {
            position: sticky;
            right: 0;
            background-color: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        }

        .table-hover tbody tr:hover .sticky-col {
            background-color: #f8f9fa;
        }

        /* Scrollbar personalizado */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-admin-layout>
