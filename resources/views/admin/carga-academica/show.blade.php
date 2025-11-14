<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-eye text-info"></i> Detalle de Carga Académica
            </h2>
            <div>
                <a href="{{ route('admin.carga-academica.edit', $cargaAcademica->id) }}" class="btn btn-warning">
                    <i class="fa-solid fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        @php
            // Obtener todos los horarios relacionados (mismo bloque de tiempo)
            $horariosRelacionados = \App\Models\Horario::where('hora_inicio', $cargaAcademica->horario->hora_inicio)
                ->where('hora_fin', $cargaAcademica->horario->hora_fin)
                ->pluck('dia_semana')
                ->toArray();
        @endphp

        <div class="row">
            {{-- Información del Docente --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-tie"></i> Docente
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar-circle-large bg-primary text-white mb-3 mx-auto">
                            {{ substr($cargaAcademica->docente->user->name, 0, 2) }}
                        </div>
                        <h4 class="mb-1">{{ $cargaAcademica->docente->user->name }}</h4>
                        <p class="text-muted mb-3">{{ $cargaAcademica->docente->user->email }}</p>

                        <div class="row text-start">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Categoría:</small>
                                <p class="mb-0"><span class="badge bg-info">{{ $cargaAcademica->docente->categoria }}</span></p>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Profesión:</small>
                                <p class="mb-0">{{ $cargaAcademica->docente->profesion ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Carga Horaria Máxima:</small>
                                <p class="mb-0"><strong>{{ $cargaAcademica->docente->cargaHoraria }} horas/semana</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gestión y Periodo --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-calendar-alt"></i> Período Académico
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Gestión:</small>
                            <h4 class="mb-0 text-primary">{{ $cargaAcademica->gestion }}</h4>
                        </div>
                        <div>
                            <small class="text-muted">Período:</small>
                            <h4 class="mb-0 text-info">{{ $cargaAcademica->periodo }}° Semestre</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detalles de la Asignación --}}
            <div class="col-lg-8">
                {{-- Información de la Materia --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book-open"></i> Materia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary me-2" style="font-size: 1.5rem;">
                                {{ $cargaAcademica->materia->sigla }}
                            </span>
                            <h4 class="mb-0">{{ $cargaAcademica->materia->nombre }}</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-tag"></i> Código:
                                </small>
                                <p class="mb-0"><strong>{{ $cargaAcademica->materia->codigo }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-layer-group"></i> Nivel:
                                </small>
                                <p class="mb-0">{{ $cargaAcademica->materia->nivel }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-clock"></i> Carga Horaria:
                                </small>
                                <p class="mb-0"><strong>{{ $cargaAcademica->materia->cargaHoraria }} horas/semana</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Grupo --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-users"></i> Grupo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Nombre del Grupo:</small>
                                <h4 class="mb-0 text-info">{{ $cargaAcademica->grupo->nombre }}</h4>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fa-solid fa-user-group"></i> Cupo Máximo:
                                </small>
                                <h4 class="mb-0 text-success">{{ $cargaAcademica->grupo->cupo_maximo }} estudiantes</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Horario --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-calendar-days"></i> Horario
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-calendar-week"></i> Días de la Semana:
                            </small>
                            <div class="mt-2">
                                @foreach($horariosRelacionados as $dia)
                                    <span class="badge bg-primary me-1 mb-1" style="font-size: 0.95rem;">{{ $dia }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-clock"></i> Hora de Inicio:
                                </small>
                                <p class="mb-0"><strong>{{ substr($cargaAcademica->horario->hora_inicio, 0, 5) }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-clock"></i> Hora de Fin:
                                </small>
                                <p class="mb-0"><strong>{{ substr($cargaAcademica->horario->hora_fin, 0, 5) }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-hourglass-half"></i> Duración:
                                </small>
                                <p class="mb-0 text-success"><strong>{{ $cargaAcademica->horario->duracion_formateada }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Aula --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-door-open"></i> Aula
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Código del Aula:</small>
                                <h4 class="mb-0 text-primary">{{ $cargaAcademica->aula->codigo }}</h4>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-tag"></i> Tipo:
                                </small>
                                <p class="mb-0"><span class="badge bg-info" style="font-size: 1rem;">{{ $cargaAcademica->aula->tipo }}</span></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fa-solid fa-chair"></i> Capacidad:
                                </small>
                                <p class="mb-0"><strong>{{ $cargaAcademica->aula->capacidad }} personas</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2.5rem;
        }
    </style>
</x-admin-layout>
