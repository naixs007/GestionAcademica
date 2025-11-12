<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-book text-primary"></i> Gestión de Materias
            </h2>
            @can('materias.crear')
                <a href="{{ route('admin.materia.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Nueva Materia
                </a>
            @endcan
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

        {{-- Tarjeta con tabla de materias --}}
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list"></i> Listado de Materias
                    </h5>
                    <span class="badge bg-info">{{ $materias->total() }} materias</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="min-width: 100px;">
                                    <i class="fa-solid fa-barcode"></i> Código
                                </th>
                                <th style="min-width: 250px;">
                                    <i class="fa-solid fa-book"></i> Nombre de la Materia
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-layer-group"></i> Nivel
                                </th>
                                <th style="min-width: 130px;">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria
                                </th>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-chalkboard-user"></i> Docente
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-users-rectangle"></i> Grupos
                                </th>
                                <th style="min-width: 200px;" class="text-center">
                                    <i class="fa-solid fa-cog"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materias as $materia)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $materia->codigo }}</span>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-book-open text-info"></i>
                                        <strong>{{ $materia->nombre }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $materia->nivel }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fa-solid fa-clock"></i> {{ $materia->cargaHoraria }} hrs/sem
                                        </span>
                                    </td>
                                    <td>
                                        @if($materia->docente)
                                            <i class="fa-solid fa-user-circle text-success"></i>
                                            <small>{{ $materia->docente->user->name }}</small>
                                        @else
                                            <span class="text-muted">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-users"></i> {{ $materia->grupos->count() }} grupos
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('materias.ver')
                                                <a href="{{ route('admin.materia.show', $materia) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver detalles">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('materias.editar')
                                                <a href="{{ route('admin.materia.edit', $materia) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Editar">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('materias.eliminar')
                                                <form action="{{ route('admin.materia.destroy', $materia) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar esta materia? No se puede eliminar si tiene grupos asignados.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Eliminar"
                                                            @if($materia->grupos->count() > 0) disabled @endif>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No hay materias registradas.</p>
                                        @can('materias.crear')
                                            <a href="{{ route('admin.materia.create') }}" class="btn btn-primary mt-3">
                                                <i class="fa-solid fa-plus"></i> Registrar Primera Materia
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $materias->links() }}
        </div>

        {{-- Información adicional --}}
        <div class="alert alert-info mt-4">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Información:</strong> Aquí puedes gestionar todas las materias del plan de estudios. 
            Cada materia puede ser asignada a un docente y dividida en grupos.
        </div>
    </div>

    <style>
        .table-responsive {
            border: 1px solid #dee2e6;
        }

        .table-responsive::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #198754;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #146c43;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.1);
        }
    </style>
</x-admin-layout>
