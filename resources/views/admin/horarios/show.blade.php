<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-clock"></i> Detalle del Horario</h2>
            <div>
                <a href="{{ route('admin.horario.edit', $horario) }}" class="btn btn-warning">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-info-circle"></i> Información del Horario</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-day"></i> Días de la Semana:</strong>
                                <p class="mb-0">
                                    @if(isset($horario->dias_relacionados) && count($horario->dias_relacionados) > 0)
                                        @foreach($horario->dias_relacionados as $dia)
                                            <span class="badge bg-primary fs-6 me-1">{{ $dia }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-primary fs-6">{{ $horario->dia_semana }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-hourglass"></i> Duración:</strong>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">
                                        {{ $horario->duracion_formateada }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-clock"></i> Hora de Inicio:</strong>
                                <p class="mb-0 h5">{{ $horario->hora_inicio }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-clock"></i> Hora de Fin:</strong>
                                <p class="mb-0 h5">{{ $horario->hora_fin }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-plus"></i> Fecha de Creación:</strong>
                                <p class="mb-0">{{ $horario->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa-solid fa-calendar-edit"></i> Última Actualización:</strong>
                                <p class="mb-0">{{ $horario->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0"><i class="fa-solid fa-tools"></i> Acciones</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.horario.edit', $horario) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fa-solid fa-edit"></i> Editar Horario
                        </a>
                        <form action="{{ route('admin.horario.destroy', $horario) }}" method="POST"
                            onsubmit="return confirm('¿Está seguro de eliminar este horario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fa-solid fa-trash"></i> Eliminar Horario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
