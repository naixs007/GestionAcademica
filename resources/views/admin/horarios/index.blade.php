<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-clock"></i> Gestión de Horarios</h2>
            <a href="{{ route('admin.horario.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nuevo Horario
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Días de la Semana</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Duración</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horarios as $horario)
                                <tr>
                                    <td>
                                        @if(isset($horario->dias_agrupados))
                                            @foreach($horario->dias_agrupados as $dia)
                                                <span class="badge bg-primary me-1">{{ $dia }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-primary">{{ $horario->dia_semana }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-clock"></i> {{ $horario->hora_inicio }}
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-clock"></i> {{ $horario->hora_fin }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $horario->duracion_formateada }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.horario.show', $horario) }}"
                                                class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.horario.edit', $horario) }}"
                                                class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.horario.destroy', $horario) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar este horario?');">
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
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-calendar-xmark fa-3x mb-3"></i>
                                        <p>No hay horarios registrados</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $horarios->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
