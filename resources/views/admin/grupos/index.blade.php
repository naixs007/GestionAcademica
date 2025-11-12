<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-users-rectangle text-primary"></i> Gestión de Grupos
            </h2>
            @can('grupos.crear')
                <a href="{{ route('admin.grupos.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Nuevo Grupo
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

        {{-- Tarjeta con tabla de grupos --}}
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list"></i> Listado de Grupos
                    </h5>
                    <span class="badge bg-info">{{ $grupos->total() }} grupos</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="min-width: 80px;">ID</th>
                                <th style="min-width: 180px;">
                                    <i class="fa-solid fa-tag"></i> Nombre del Grupo
                                </th>
                                <th style="min-width: 250px;">
                                    <i class="fa-solid fa-book"></i> Materia
                                </th>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-chalkboard-user"></i> Docente
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-users"></i> Capacidad
                                </th>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-calendar"></i> Registro
                                </th>
                                <th style="min-width: 200px;" class="text-center">
                                    <i class="fa-solid fa-cog"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grupos as $grupo)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $grupo->id }}</span></td>
                                    <td>
                                        <i class="fa-solid fa-users-rectangle text-primary"></i>
                                        <strong>{{ $grupo->nombre }}</strong>
                                    </td>
                                    <td>
                                        @if($grupo->materias)
                                            <span class="badge bg-primary">
                                                {{ $grupo->materias->codigo ?? 'N/A' }}
                                            </span>
                                            {{ $grupo->materias->nombre }}
                                        @else
                                            <span class="text-muted">Sin materia asignada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($grupo->materias && $grupo->materias->docente)
                                            <i class="fa-solid fa-user-circle text-success"></i>
                                            <small>{{ $grupo->materias->docente->user->name }}</small>
                                        @else
                                            <span class="text-muted">Sin docente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fa-solid fa-users"></i> {{ $grupo->capacidad }} estudiantes
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-calendar-day"></i>
                                            {{ $grupo->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('grupos.ver')
                                                <a href="{{ route('admin.grupos.show', $grupo) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver detalles">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('grupos.editar')
                                                <a href="{{ route('admin.grupos.edit', $grupo) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Editar">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('grupos.eliminar')
                                                <form action="{{ route('admin.grupos.destroy', $grupo) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar este grupo?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Eliminar">
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
                                        <p class="text-muted mb-0">No hay grupos registrados.</p>
                                        @can('grupos.crear')
                                            <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary mt-3">
                                                <i class="fa-solid fa-plus"></i> Registrar Primer Grupo
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
            {{ $grupos->links() }}
        </div>

        {{-- Información adicional --}}
        <div class="alert alert-info mt-4">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Información:</strong> Aquí puedes gestionar todos los grupos académicos. 
            Cada grupo está asociado a una materia y tiene una capacidad máxima de estudiantes.
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
