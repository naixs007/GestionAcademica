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
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-list-ol"></i> Siga estos pasos para registrar la asistencia
                </h5>
            </div>
            <div class="card-body">
                {{-- Indicador de Pasos --}}
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-info-circle me-2 fs-4"></i>
                        <div>
                            <strong>Orden de captura:</strong>
                            <ol class="mb-0 mt-2">
                                <li><strong>Paso 1:</strong> Seleccione el docente</li>
                                <li><strong>Paso 2:</strong> El sistema cargará sus materias automáticamente</li>
                                <li><strong>Paso 3:</strong> Seleccione la materia, grupo y horario específico</li>
                                <li><strong>Paso 4:</strong> Complete los datos de asistencia</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.asistencia.store') }}" method="POST" id="formAsistencia">
                    @csrf

                    <div class="row">
                        {{-- Seleccionar Docente --}}
                        <div class="col-md-6 mb-3">
                            <label for="docente_id" class="form-label">
                                <span class="badge bg-primary me-1">Paso 1</span>
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
                                <i class="fa-solid fa-arrow-right"></i> Al seleccionar, se cargarán sus materias
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
                            <span class="badge bg-secondary me-1" id="badge-paso2">Paso 2</span>
                            Materia / Grupo / Horario <span class="text-danger">*</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" id="loading-cargas" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </span>
                        </label>
                        <select id="carga_academica_id"
                                name="carga_academica_id"
                                class="form-select @error('carga_academica_id') is-invalid @enderror"
                                required
                                disabled>
                            <option value="">
                                <i class="fa-solid fa-arrow-up"></i> Primero seleccione un docente en el Paso 1
                            </option>
                        </select>
                        @error('carga_academica_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted" id="help-carga">
                            <i class="fa-solid fa-lock"></i> Este campo se habilitará automáticamente al seleccionar un docente
                        </small>
                    </div>

                    <div class="row">
                        {{-- Estado --}}
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">
                                <span class="badge bg-secondary me-1">Paso 3</span>
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

    <script>
        console.log('=== SCRIPT INICIANDO ===');

        (function() {
            console.log('Script ejecutándose inmediatamente');

            function inicializarFormulario() {
                console.log('Función inicializarFormulario llamada');

                const docenteSelect = document.getElementById('docente_id');
                const cargaSelect = document.getElementById('carga_academica_id');
                const estadoSelect = document.getElementById('estado');
                const horaLlegadaGroup = document.getElementById('hora-llegada-group');
                const horaLlegadaInput = document.getElementById('hora_llegada');
                const loadingSpinner = document.getElementById('loading-cargas');
                const badgePaso2 = document.getElementById('badge-paso2');
                const helpCarga = document.getElementById('help-carga');

                console.log('Elementos encontrados:', {
                    docenteSelect: !!docenteSelect,
                    cargaSelect: !!cargaSelect,
                    loadingSpinner: !!loadingSpinner,
                    badgePaso2: !!badgePaso2,
                    helpCarga: !!helpCarga
                });

                if (!docenteSelect) {
                    console.error('ERROR: No se encontró el select de docente');
                    return;
                }

                // Cargar materias cuando se selecciona un docente
                docenteSelect.addEventListener('change', function() {
                    const docenteId = this.value;
                    console.log('=== EVENTO CHANGE DISPARADO ===');
                    console.log('Docente seleccionado ID:', docenteId);

                    // Mostrar spinner
                    loadingSpinner.classList.remove('d-none');
                    badgePaso2.classList.remove('bg-secondary', 'bg-success', 'bg-danger');
                    badgePaso2.classList.add('bg-warning');
                    badgePaso2.textContent = 'Paso 2 - Cargando...';

                    cargaSelect.innerHTML = '<option value="">⏳ Cargando...</option>';
                    cargaSelect.disabled = true;
                    cargaSelect.classList.remove('is-valid');

                    if (!docenteId) {
                        console.log('Docente vacío, reseteando');
                        loadingSpinner.classList.add('d-none');
                        badgePaso2.classList.remove('bg-warning');
                        badgePaso2.classList.add('bg-secondary');
                        badgePaso2.textContent = 'Paso 2';
                        cargaSelect.innerHTML = '<option value="">⬆️ Primero seleccione un docente</option>';
                        helpCarga.innerHTML = '<i class="fa-solid fa-lock"></i> Este campo se habilitará automáticamente';
                        return;
                    }

                    const url = '{{ url("admin/asistencia/get-materias") }}/' + docenteId;
                    console.log('Haciendo fetch a:', url);

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response recibido, status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response:', text);
                                throw new Error('Error ' + response.status);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        console.log('Cantidad:', Array.isArray(data) ? data.length : 'No es array');

                        loadingSpinner.classList.add('d-none');

                        if (!Array.isArray(data) || data.length === 0) {
                            badgePaso2.classList.remove('bg-warning');
                            badgePaso2.classList.add('bg-danger');
                            badgePaso2.textContent = 'Sin datos';
                            cargaSelect.innerHTML = '<option value="">⚠️ Sin asignaciones</option>';
                            helpCarga.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-warning"></i> Este docente no tiene cargas asignadas';
                        } else {
                            badgePaso2.classList.remove('bg-warning');
                            badgePaso2.classList.add('bg-success');
                            badgePaso2.textContent = 'Paso 2 ✓';

                            cargaSelect.innerHTML = '<option value="">-- Seleccione --</option>';

                            data.forEach(carga => {
                                const option = document.createElement('option');
                                option.value = carga.id;

                                const materia = carga.materia?.nombre || 'N/A';
                                const codigo = carga.materia?.codigo ? '(' + carga.materia.codigo + ')' : '';
                                const grupo = carga.grupo?.nombre || 'N/A';
                                const dia = carga.horario?.dia_semana || 'N/A';
                                const inicio = carga.horario?.hora_inicio?.substring(0,5) || '';
                                const fin = carga.horario?.hora_fin?.substring(0,5) || '';
                                const aula = carga.aula?.nombre || 'Sin aula';

                                option.textContent = `📚 ${materia} ${codigo} | 👥 ${grupo} | 📅 ${dia} ${inicio}-${fin} | 🚪 ${aula}`;
                                cargaSelect.appendChild(option);
                            });

                            cargaSelect.disabled = false;
                            helpCarga.innerHTML = '<i class="fa-solid fa-check-circle text-success"></i> ' + data.length + ' asignación(es) encontrada(s)';
                            console.log('Select habilitado con', data.length, 'opciones');
                        }
                    })
                    .catch(error => {
                        console.error('ERROR:', error);
                        loadingSpinner.classList.add('d-none');
                        badgePaso2.classList.remove('bg-warning');
                        badgePaso2.classList.add('bg-danger');
                        badgePaso2.textContent = 'Error';
                        cargaSelect.innerHTML = '<option value="">❌ Error</option>';
                        helpCarga.innerHTML = '<i class="fa-solid fa-exclamation-circle text-danger"></i> ' + error.message;
                    });
                });

                // Feedback visual al seleccionar carga
                cargaSelect.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });

                // Mostrar/ocultar hora de llegada
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

                // Inicializar hora de llegada
                if (estadoSelect.value !== 'Tardanza') {
                    horaLlegadaGroup.style.display = 'none';
                    horaLlegadaInput.required = false;
                }

                console.log('Inicialización completa - Event listeners agregados');
            }

            // Ejecutar cuando DOM esté listo
            if (document.readyState === 'loading') {
                console.log('DOM aún cargando, esperando...');
                document.addEventListener('DOMContentLoaded', inicializarFormulario);
            } else {
                console.log('DOM ya está listo, ejecutando inmediatamente');
                inicializarFormulario();
            }
        })();
    </script>
</x-admin-layout>
