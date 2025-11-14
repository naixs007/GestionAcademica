<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-pencil-square me-2"></i>
                Editar Asistencia
            </h2>
            <a href="{{ route('admin.asistencia.show', $asistencia) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver
            </a>
        </div>

        {{-- Información de la Asignación (Solo lectura) --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Información de la Asignación
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Docente</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-person me-1"></i>
                            {{ $asistencia->docente->user->name ?? 'N/A' }}
                        </p>
                    </div>

                    @if($asistencia->materia)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Materia</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-book me-1"></i>
                            {{ $asistencia->materia->nombre }}
                        </p>
                    </div>
                    @endif

                    @if($asistencia->grupo)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Grupo</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-people me-1"></i>
                            {{ $asistencia->grupo->codigo }}
                        </p>
                    </div>
                    @endif

                    @if($asistencia->horario)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Horario</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-clock me-1"></i>
                            {{ $asistencia->horario->dia_semana }}
                            {{ \Carbon\Carbon::parse($asistencia->horario->hora_inicio)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($asistencia->horario->hora_fin)->format('H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Esta información no se puede modificar. Solo puede editar la fecha, estado y observaciones.
                </div>
            </div>
        </div>

        {{-- Formulario de Edición --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.asistencia.update', $asistencia) }}" method="POST" id="formEditAsistencia">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Fecha --}}
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="fecha"
                                   name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', $asistencia->fecha) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select id="estado"
                                    name="estado"
                                    class="form-select @error('estado') is-invalid @enderror"
                                    required>
                                <option value="">-- Seleccione --</option>
                                <option value="Presente" {{ old('estado', $asistencia->estado) == 'Presente' ? 'selected' : '' }}>
                                    ✓ Presente
                                </option>
                                <option value="Ausente" {{ old('estado', $asistencia->estado) == 'Ausente' ? 'selected' : '' }}>
                                    ✗ Ausente
                                </option>
                                <option value="Tardanza" {{ old('estado', $asistencia->estado) == 'Tardanza' ? 'selected' : '' }}>
                                    ⚠ Tardanza
                                </option>
                                <option value="Justificado" {{ old('estado', $asistencia->estado) == 'Justificado' ? 'selected' : '' }}>
                                    ℹ Justificado
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hora de Llegada --}}
                    <div class="mb-3" id="hora-llegada-group" style="display: {{ old('estado', $asistencia->estado) == 'Tardanza' ? 'block' : 'none' }};">
                        <label for="hora_llegada" class="form-label">
                            Hora de Llegada
                        </label>
                        <input type="time"
                               id="hora_llegada"
                               name="hora_llegada"
                               class="form-control @error('hora_llegada') is-invalid @enderror"
                               value="{{ old('hora_llegada', $asistencia->hora_llegada ? \Carbon\Carbon::parse($asistencia->hora_llegada)->format('H:i') : '') }}">
                        @error('hora_llegada')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Requerido solo para tardanzas
                        </small>
                    </div>

                    {{-- Observaciones --}}
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones"
                                  name="observaciones"
                                  class="form-control @error('observaciones') is-invalid @enderror"
                                  rows="4">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.asistencia.show', $asistencia) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <a href="{{ route('admin.asistencia.index') }}" class="btn btn-outline-secondary">
                            Volver al listado
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('estado');
            const horaLlegadaGroup = document.getElementById('hora-llegada-group');
            const horaLlegadaInput = document.getElementById('hora_llegada');

            // Mostrar/ocultar hora de llegada según el estado
            estadoSelect.addEventListener('change', function() {
                if (this.value === 'Tardanza') {
                    horaLlegadaGroup.style.display = 'block';
                    horaLlegadaInput.required = true;
                } else {
                    horaLlegadaGroup.style.display = 'none';
                    horaLlegadaInput.required = false;
                    horaLlegadaInput.value = '';
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
