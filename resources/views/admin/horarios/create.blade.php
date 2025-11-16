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
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fa-solid fa-calendar-week"></i> Días de la Semana <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-3 bg-light @error('dias_semana') border-danger @enderror">
                                @php
                                    $dias = [
                                        'Lunes' => 'L',
                                        'Martes' => 'M',
                                        'Miércoles' => 'M',
                                        'Jueves' => 'J',
                                        'Viernes' => 'V',
                                        'Sábado' => 'S'
                                    ];
                                    $oldDias = old('dias_semana', []);
                                @endphp
                                <div class="row g-2">
                                    @foreach ($dias as $diaCompleto => $diaCorto)
                                        <div class="col-6 col-md-4 col-lg-2">
                                            <input class="btn-check" type="checkbox" name="dias_semana[]"
                                                value="{{ $diaCompleto }}" id="dia{{ $loop->index }}"
                                                {{ in_array($diaCompleto, $oldDias) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary w-100" for="dia{{ $loop->index }}">
                                                <i class="fa-solid fa-calendar-day d-block mb-1"></i>
                                                <small class="d-block">{{ $diaCompleto }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">
                                        <i class="fa-solid fa-check-double"></i> Seleccionar Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="selectNone">
                                        <i class="fa-solid fa-times"></i> Limpiar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="selectWeekdays">
                                        <i class="fa-solid fa-briefcase"></i> Lun-Vie
                                    </button>
                                </div>
                            </div>
                            @error('dias_semana')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Click en los días para seleccionar/deseleccionar</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                class="form-control @error('hora_inicio') is-invalid @enderror"
                                value="{{ old('hora_inicio', '07:00') }}" required>
                            @error('hora_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formato 24 horas (ej: 07:00, 14:30)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                class="form-control @error('hora_fin') is-invalid @enderror"
                                value="{{ old('hora_fin', '08:30') }}" required>
                            @error('hora_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Debe ser posterior a hora de inicio</small>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const selectNone = document.getElementById('selectNone');
            const selectWeekdays = document.getElementById('selectWeekdays');
            const checkboxes = document.querySelectorAll('input[name="dias_semana[]"]');

            selectAll.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });

            selectNone.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });

            selectWeekdays.addEventListener('click', function() {
                checkboxes.forEach((cb, index) => {
                    // Marcar Lunes a Viernes (índices 0-4), desmarcar Sábado (índice 5)
                    cb.checked = index < 5;
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
