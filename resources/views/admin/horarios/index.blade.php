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
                                <th>Materia</th>
                                <th>Día</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Modalidad</th>
                                <th>Asistencias</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horarios as $horario)
                                <tr>
                                    <td>
                                        <strong>{{ $horario->materia->nombre ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $horario->materia->codigo ?? '' }}</small>
                                    </td>
                                    <td>
                                        @if (is_array($horario->diaSemana))
                                            @foreach ($horario->diaSemana as $dia)
                                                <span class="badge bg-secondary me-1">{{ $dia }}</span>
                                            @endforeach
                                        @else
                                            {{ $horario->diaSemana }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($horario->horaInicio)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($horario->horaFin)->format('H:i') }}</td>
                                    <td>
                                        @if ($horario->modalidad === 'presencial')
                                            <span class="badge bg-primary">
                                                <i class="fa-solid fa-building"></i> Presencial
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="fa-solid fa-laptop"></i> Virtual
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $horario->asistencias->count() ?? 0 }}
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
                                    <td colspan="7" class="text-center text-muted py-4">
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
