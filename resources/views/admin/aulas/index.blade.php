<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-door-open"></i> Gestión de Aulas</h2>
            <a href="{{ route('admin.aula.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nueva Aula
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
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Capacidad</th>
                                <th>Materias Asignadas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aulas as $aula)
                                <tr>
                                    <td>
                                        <strong>{{ $aula->codigo }}</strong>
                                    </td>
                                    <td>
                                        @if ($aula->tipo === 'Presencial')
                                            <span class="badge bg-primary">
                                                <i class="fa-solid fa-chalkboard"></i> Presencial
                                            </span>
                                        @elseif ($aula->tipo === 'Laboratorio')
                                            <span class="badge bg-info">
                                                <i class="fa-solid fa-flask"></i> Laboratorio
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fa-solid fa-laptop"></i> Virtual
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-users"></i> {{ $aula->capacidad }} personas
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $aula->materias_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.aula.show', $aula) }}" class="btn btn-sm btn-info"
                                                title="Ver detalles">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.aula.edit', $aula) }}"
                                                class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.aula.destroy', $aula) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar esta aula/laboratorio?');">
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
                                        <i class="fa-solid fa-door-closed fa-3x mb-3"></i>
                                        <p>No hay aulas registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $aulas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
