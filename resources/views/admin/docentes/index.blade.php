<x-admin-layout>
    <div class="container-fluid py-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-chalkboard-user"></i> Gestión de Docentes</h2>
        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Docente
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
                        <i class="fa-solid fa-list"></i> Listado de Docentes
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
                                    <i class="fa-solid fa-user"></i> Nombre Completo
                                </th>
                                <th style="min-width: 180px;">
                                    <i class="fa-solid fa-envelope"></i> Correo
                                </th>
                                <th style="min-width: 120px;">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria
                                </th>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-tag"></i> Categoría
                                </th>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-graduation-cap"></i> Profesión
                                </th>
                                <th style="min-width: 180px;">
                                    <i class="fa-solid fa-book"></i> Materias
                                </th>
                                <th style="min-width: 200px;" class="text-center">
                                    <i class="fa-solid fa-cog"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($docentes as $docente)
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-user-circle text-primary"></i>
                                        <strong>{{ $docente->user->name }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-at"></i> {{ $docente->user->email }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $docente->cargaHoraria }} hrs
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $docente->categoria }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $docente->profesion ?? 'No especificada' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-book"></i> {{ $docente->materias->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.docentes.show', $docente) }}"
                                               class="btn btn-sm btn-info"
                                               title="Ver detalles">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.docentes.edit', $docente) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.docentes.destroy', $docente) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este docente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No hay docentes registrados.</p>
                                        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary mt-3">
                                            <i class="fa-solid fa-plus"></i> Registrar Primer Docente
                                        </a>
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
            {{ $docentes->links() }}
        </div>

        {{-- Información adicional --}}
        <div class="alert alert-info mt-4">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Información:</strong> Aquí puedes gestionar todos los docentes registrados en el sistema.
            Puedes ver sus materias asignadas, carga horaria y datos personales.
        </div>
    </div>

    <style>
        /* Estilos para la tabla con scroll */
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
