<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-calendar-check me-2"></i>
                Registrar Asistencia de Docente
            </h2>
            <a href="{{ route('admin.asistencia.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.asistencia.store') }}" method="POST" id="formAsistencia">
                    @csrf

                    <div class="row">
                        {{-- Seleccionar Docente --}}
                        <div class="col-md-6 mb-3">
                            <label for="docente_id" class="form-label">
                                Docente <span class="text-danger">*</span>
                            </label>
                            <select id="docente_id" name="docente_id" class="form-select @error('docente_id') is-invalid @enderror" required>
                                <option value="">-- Seleccione un docente --</option>
                                @foreach($docentesConCargas as $docenteId => $data)
                                    <option value="{{ $docenteId }}" {{ old('docente_id') == $docenteId ? 'selected' : '' }}>
                                        {{ $data['docente']->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('docente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Seleccione el docente para ver sus asignaciones
                            </small>
                        </div>

                        {{-- Fecha --}}
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="fecha"
                                   name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Seleccionar Asignación (Carga Académica) --}}
                    <div class="mb-3">
                        <label for="carga_academica_id" class="form-label">
                            Materia / Grupo / Horario <span class="text-danger">*</span>
                        </label>
                        <select id="carga_academica_id"
                                name="carga_academica_id"
                                class="form-select @error('carga_academica_id') is-invalid @enderror"
                                required
                                disabled>
                            <option value="">-- Primero seleccione un docente --</option>
                        </select>
                        @error('carga_academica_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Seleccione la asignación específica (materia, grupo y horario)
                        </small>
                    </div>

                    <div class="row">
                        {{-- Estado --}}
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Presente" {{ old('estado') == 'Presente' ? 'selected' : '' }}>
                                    ✓ Presente
                                </option>
                                <option value="Ausente" {{ old('estado') == 'Ausente' ? 'selected' : '' }}>
                                    ✗ Ausente
                                </option>
                                <option value="Tardanza" {{ old('estado') == 'Tardanza' ? 'selected' : '' }}>
                                    ⚠ Tardanza
                                </option>
                                <option value="Justificado" {{ old('estado') == 'Justificado' ? 'selected' : '' }}>
                                    ℹ Justificado
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hora de Llegada --}}
                        <div class="col-md-4 mb-3" id="hora-llegada-group">
                            <label for="hora_llegada" class="form-label">
                                Hora de Llegada
                            </label>
                            <input type="time"
                                   id="hora_llegada"
                                   name="hora_llegada"
                                   class="form-control @error('hora_llegada') is-invalid @enderror"
                                   value="{{ old('hora_llegada') }}">
                            @error('hora_llegada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Requerido solo para tardanzas
                            </small>
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones"
                                  name="observaciones"
                                  class="form-control @error('observaciones') is-invalid @enderror"
                                  rows="3">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Registrar Asistencia
                        </button>
                        <a href="{{ route('admin.asistencia.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const docenteSelect = document.getElementById('docente_id');
            const cargaSelect = document.getElementById('carga_academica_id');
            const estadoSelect = document.getElementById('estado');
            const horaLlegadaGroup = document.getElementById('hora-llegada-group');
            const horaLlegadaInput = document.getElementById('hora_llegada');

            // Cargar materias cuando se selecciona un docente
            docenteSelect.addEventListener('change', function() {
                const docenteId = this.value;

                // Limpiar y deshabilitar el select de carga académica
                cargaSelect.innerHTML = '<option value="">-- Cargando... --</option>';
                cargaSelect.disabled = true;

                if (!docenteId) {
                    cargaSelect.innerHTML = '<option value="">-- Primero seleccione un docente --</option>';
                    return;
                }

                // Hacer petición AJAX
                fetch(`{{ url('admin/asistencia/get-materias') }}/${docenteId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al cargar las asignaciones');
                        }
                        return response.json();
                    })
                    .then(data => {
                        cargaSelect.innerHTML = '<option value="">-- Seleccione una asignación --</option>';

                        if (data.length === 0) {
                            cargaSelect.innerHTML = '<option value="">-- No hay asignaciones para este docente --</option>';
                        } else {
                            data.forEach(carga => {
                                const option = document.createElement('option');
                                option.value = carga.id;

                                // Formatear el texto de la opción
                                const materia = carga.materia ? carga.materia.nombre : 'N/A';
                                const grupo = carga.grupo ? carga.grupo.codigo : 'N/A';
                                const horario = carga.horario ?
                                    `${carga.horario.dia_semana} ${carga.horario.hora_inicio}-${carga.horario.hora_fin}` :
                                    'N/A';
                                const aula = carga.aula ? carga.aula.nombre : 'N/A';

                                option.textContent = `${materia} | Grupo: ${grupo} | ${horario} | Aula: ${aula}`;
                                cargaSelect.appendChild(option);
                            });
                            cargaSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        cargaSelect.innerHTML = '<option value="">-- Error al cargar asignaciones --</option>';
                    });
            });

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

            // Inicializar el estado de hora de llegada
            if (estadoSelect.value !== 'Tardanza') {
                horaLlegadaGroup.style.display = 'none';
                horaLlegadaInput.required = false;
            }
        });
    </script>
    @endpush
</x-admin-layout>
