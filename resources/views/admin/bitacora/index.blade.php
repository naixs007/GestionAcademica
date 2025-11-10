<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Bitácora de acciones</h2>
            <form method="GET" class="d-flex" action="{{ route('bitacora.index') }}">
                <input type="text" name="action" class="form-control me-2" placeholder="Filtrar por acción" value="{{ request('action') }}">
                <button class="btn btn-outline-secondary">Filtrar</button>
            </form>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Descripción</th>
                            <th>IP</th>
                            <th>Navegador</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bitacora as $entry)
                            <tr>
                                <td>{{ $entry->user->name ?? $entry->usuario ?? 'Sistema' }}</td>
                                <td>{{ $entry->metodo ?? $entry->action ?? $entry->usuario ?? '-' }}</td>
                                <td>{{ $entry->descripcion ?? $entry->description ?? '-' }}</td>
                                <td>{{ $entry->direccion_ip ?? $entry->ip_address ?? '-' }}</td>
                                <td>{{ $entry->navegador ?? $entry->browser ?? '-' }}</td>
                                <td>{{ optional($entry->fecha_hora ?? $entry->created_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay registros en la bitácora.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $bitacora->links() }}
        </div>
    </div>
</x-admin-layout>
