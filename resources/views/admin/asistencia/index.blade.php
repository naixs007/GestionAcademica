<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Asistencias</h2>
            <a href="{{ route('admin.asistencia.create') }}" class="btn btn-primary">Registrar asistencia</a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asistencias as $a)
                            <tr>
                                <td>{{ $a->fecha }}</td>
                                <td>{{ $a->estado ? 'Sí' : 'No' }}</td>
                                <td>{{ $a->observaciones ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.asistencia.edit', $a) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                    <form action="{{ route('admin.asistencia.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar asistencia?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay registros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $asistencias->links() }}
        </div>
    </div>
</x-admin-layout>
