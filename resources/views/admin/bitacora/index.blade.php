<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-file-lines text-primary"></i> Bitácora de Acciones
            </h2>
            <form method="GET" class="d-flex" action="{{ route('admin.bitacora.index') }}">
                <input type="text" name="action" class="form-control me-2" placeholder="Filtrar por acción o usuario" value="{{ request('action') }}">
                <button class="btn btn-outline-secondary">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list"></i> Registro de Actividades
                    </h5>
                    <span class="badge bg-info">{{ $bitacora->total() }} registros</span>
                </div>
            </div>
            <div class="card-body p-0">
                {{-- Tabla con scroll horizontal --}}
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="min-width: 150px;">
                                    <i class="fa-solid fa-user"></i> Usuario
                                </th>
                                <th style="min-width: 180px;">
                                    <i class="fa-solid fa-bolt"></i> Acción
                                </th>
                                <th style="min-width: 300px;">
                                    <i class="fa-solid fa-align-left"></i> Descripción
                                </th>
                                <th style="min-width: 130px;">
                                    <i class="fa-solid fa-network-wired"></i> Dirección IP
                                </th>
                                <th style="min-width: 200px;">
                                    <i class="fa-solid fa-browser"></i> Navegador
                                </th>
                                <th style="min-width: 160px;">
                                    <i class="fa-solid fa-clock"></i> Fecha y Hora
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bitacora as $entry)
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-user-circle text-muted"></i>
                                        <strong>{{ $entry->user->name ?? $entry->usuario ?? 'Sistema' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $entry->metodo ?? $entry->action ?? $entry->usuario ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $entry->descripcion ?? $entry->description ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <code class="text-muted">{{ $entry->direccion_ip ?? $entry->ip_address ?? '-' }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($entry->navegador ?? $entry->browser ?? '-', 40) }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fa-solid fa-calendar-day"></i>
                                            {{ $entry->fecha_hora ?? $entry->created_at->format('d/m/Y H:i:s') }}
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No hay registros en la bitácora.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $bitacora->links() }}
        </div>

        {{-- Indicador de scroll --}}
        <div class="alert alert-info mt-3">
            <i class="fa-solid fa-arrows-left-right"></i>
            <strong>Tip:</strong> Usa el scroll horizontal para ver todas las columnas de la tabla. Puedes hacer scroll vertical y horizontal dentro de la tabla.
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

        /* Header fijo al hacer scroll */
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Efecto hover en las filas */
        .table-hover tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.1);
            cursor: pointer;
        }
    </style>
</x-admin-layout>
