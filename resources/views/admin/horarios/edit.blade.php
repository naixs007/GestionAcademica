<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-edit"></i> Editar Horario</h2>
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
                <form action="{{ route('admin.horario.update', $horario) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                    $oldDias = old('dias_semana', $horario->dias_relacionados ?? [$horario->dia_semana]);
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
                                value="{{ old('hora_inicio', substr($horario->hora_inicio, 0, 5)) }}"
                                required>
                            @error('hora_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formato 24 horas</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                class="form-control @error('hora_fin') is-invalid @enderror"
                                value="{{ old('hora_fin', substr($horario->hora_fin, 0, 5)) }}"
                                required>
                            @error('hora_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Actualizar Horario
                            </button>
                            <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
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
                    cb.checked = index < 5; // Lunes a Viernes
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
