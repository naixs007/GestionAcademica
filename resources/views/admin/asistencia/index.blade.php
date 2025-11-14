<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h2 class="h4 mb-0">Control de Asistencias</h2>
            <a href="{{ route('admin.asistencia.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar asistencia
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="d-none d-md-table-cell">Observaciones</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $a)
                                <tr>
                                    <td>{{ $a->fecha }}</td>
                                    <td>
                                        <span class="badge bg-{{ $a->estado ? 'success' : 'danger' }}">
                                            {{ $a->estado ? 'Presente' : 'Ausente' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $a->observaciones ?? '-' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.asistencia.edit', $a) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-lg-inline"> Editar</span>
                                            </a>
                                            <form action="{{ route('admin.asistencia.destroy', $a) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar asistencia?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                    <span class="d-none d-lg-inline"> Eliminar</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No hay registros de asistencia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $asistencias->links() }}
        </div>
    </div>
</x-admin-layout>
