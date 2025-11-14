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

        {{-- Tarjeta con tabla de cargas académicas --}}
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list"></i> Asignaciones de Carga Académica
                    </h5>
                    <span class="badge bg-info">{{ $cargas->total() }} asignaciones</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="min-width: 180px;">
                                    <i class="fa-solid fa-chalkboard-user"></i> Docente
                                </th>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-book"></i> Materia
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-users-rectangle"></i> Grupo
                                </th>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-clock"></i> Horario
                                </th>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-door-open"></i> Aula
                                </th>
                                <th style="min-width: 100px;">
                                    <i class="fa-solid fa-calendar"></i> Gestión
                                </th>
                                <th class="text-center sticky-col" style="min-width: 150px;">
                                    <i class="fa-solid fa-cogs"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cargas as $carga)
                                @php
                                    // Obtener horarios relacionados con las mismas horas
                                    $horariosRelacionados = \App\Models\Horario::where('hora_inicio', $carga->horario->hora_inicio)
                                        ->where('hora_fin', $carga->horario->hora_fin)
                                        ->pluck('dia_semana')
                                        ->toArray();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($carga->docente->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $carga->docente->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $carga->docente->categoria }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $carga->materia->sigla }}</strong> - {{ $carga->materia->nombre }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-layer-group"></i> Nivel {{ $carga->materia->nivel }} |
                                            <i class="fa-solid fa-clock"></i> {{ $carga->materia->cargaHoraria }}h
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $carga->grupo->nombre }}</span>
                                        <br>
                                        <small class="text-muted">Cupo: {{ $carga->grupo->cupo_maximo }}</small>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            @foreach($horariosRelacionados as $dia)
                                                <span class="badge bg-primary me-1">{{ $dia }}</span>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-clock"></i>
                                            {{ substr($carga->horario->hora_inicio, 0, 5) }} - {{ substr($carga->horario->hora_fin, 0, 5) }}
                                            ({{ $carga->horario->duracion_formateada }})
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $carga->aula->codigo }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $carga->aula->tipo }} | Cap: {{ $carga->aula->capacidad }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $carga->gestion }}</span>
                                        <br>
                                        <small class="text-muted">Periodo {{ $carga->periodo }}</small>
                                    </td>
                                    <td class="text-center sticky-col">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.carga-academica.show', $carga) }}"
                                               class="btn btn-sm btn-info"
                                               title="Ver detalles">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.carga-academica.edit', $carga) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.carga-academica.destroy', $carga) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar esta asignación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay asignaciones de carga académica registradas.</p>
                                        <a href="{{ route('admin.carga-academica.create') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Nueva Asignación
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($cargas->hasPages())
                <div class="card-footer">
                    {{ $cargas->links() }}
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
