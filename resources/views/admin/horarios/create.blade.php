<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-clock"></i> Crear Nuevo Horario</h2>
            <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle"></i> <strong>Errores de validación:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.horario.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fa-solid fa-calendar-day"></i> Días de la Semana <span class="text-danger">*</span>
                            </label>
                            <div class="@error('dias_semana') is-invalid @enderror">
                                @php
                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                    $oldDias = old('dias_semana', []);
                                @endphp
                                @foreach ($dias as $dia)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_semana[]"
                                            value="{{ $dia }}" id="dia{{ $loop->index }}"
                                            {{ in_array($dia, $oldDias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dia{{ $loop->index }}">
                                            {{ $dia }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('dias_semana')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Seleccione al menos un día</small>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="hora_inicio" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                class="form-control @error('hora_inicio') is-invalid @enderror"
                                value="{{ old('hora_inicio') }}" required>
                            @error('hora_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formato: HH:MM (24 horas)</small>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="hora_fin" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                class="form-control @error('hora_fin') is-invalid @enderror"
                                value="{{ old('hora_fin') }}" required>
                            @error('hora_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formato: HH:MM (24 horas)</small>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Guardar Horario
                            </button>
                            <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <i class="fa-solid fa-info-circle"></i> Información
            </div>
            <div class="card-body">
                <ul>
                    <li>Todos los campos marcados con <span class="text-danger">*</span> son obligatorios.</li>
                    <li>Puede seleccionar múltiples días para crear varios bloques horarios a la vez.</li>
                    <li>La hora de fin debe ser posterior a la hora de inicio.</li>
                    <li>Use formato de 24 horas para las horas (ejemplo: 07:00, 08:30, 14:45).</li>
                    <li>Los horarios son bloques genéricos que se asignan a materias específicas en la carga académica.</li>
                    <li>Ejemplo: Lunes a Viernes, 07:00 - 08:30 (periodo matutino).</li>
                </ul>
            </div>
        </div>
    </div>
</x-admin-layout>
