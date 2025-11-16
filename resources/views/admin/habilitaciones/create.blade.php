<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-toggle-on me-2"></i>
                Habilitar Marcado de Asistencia
            </h2>
            <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver
            </a>
        </div>

        {{-- Instrucciones --}}
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-info-circle me-3 fs-4"></i>
                <div>
                    <strong>¿Qué hace esta función?</strong>
                    <p class="mb-2">Al crear una habilitación, el docente seleccionado podrá <strong>marcar su propia asistencia</strong> para la clase especificada en la fecha indicada.</p>
                    <ul class="mb-0">
                        <li>El docente verá un botón para marcar asistencia</li>
                        <li>Deberá ingresar su contraseña para confirmar</li>
                        <li>La habilitación solo funciona en la fecha seleccionada</li>
                        <li>Una vez utilizada, no puede reutilizarse</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-plus-circle"></i> Nueva Habilitación
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.habilitaciones.store') }}" method="POST" id="formHabilitacion">
                    @csrf

                    <div class="row">
                        {{-- Seleccionar Docente --}}
                        <div class="col-md-6 mb-3">
                            <label for="docente_id" class="form-label">
                                <span class="badge bg-primary me-1">1</span>
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
                                <i class="fa-solid fa-user"></i> Seleccione el docente que podrá marcar asistencia
                            </small>
                        </div>

                        {{-- Fecha --}}
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">
                                <span class="badge bg-primary me-1">3</span>
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="fecha"
                                   name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fa-solid fa-calendar"></i> Fecha en que el docente podrá marcar
                            </small>
                        </div>
                    </div>

                    {{-- Seleccionar Materia/Grupo/Horario --}}
                    <div class="mb-3">
                        <label for="carga_academica_id" class="form-label">
                            <span class="badge bg-secondary me-1" id="badge-paso2">2</span>
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
                            <option value="">⬆️ Primero seleccione un docente</option>
                        </select>
                        @error('carga_academica_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted" id="help-carga">
                            <i class="fa-solid fa-lock"></i> Se habilitará al seleccionar un docente
                        </small>
                    </div>

                    {{-- Observaciones --}}
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="fa-solid fa-comment"></i> Observaciones
                        </label>
                        <textarea id="observaciones"
                                  name="observaciones"
                                  class="form-control @error('observaciones') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Ej: Habilitación por solicitud del docente, clase de recuperación, etc.">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Resumen antes de guardar --}}
                    <div class="alert alert-light border" id="resumen" style="display: none;">
                        <h6 class="alert-heading"><i class="fa-solid fa-clipboard-check"></i> Resumen de la habilitación:</h6>
                        <p class="mb-1"><strong>Docente:</strong> <span id="resumen-docente">-</span></p>
                        <p class="mb-1"><strong>Clase:</strong> <span id="resumen-clase">-</span></p>
                        <p class="mb-1"><strong>Fecha:</strong> <span id="resumen-fecha">-</span></p>
                        <hr>
                        <small class="text-muted">
                            <i class="fa-solid fa-shield-check"></i> El docente deberá ingresar su contraseña para marcar la asistencia
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-toggle-on me-1"></i>
                            Crear Habilitación
                        </button>
                        <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const docenteSelect = document.getElementById('docente_id');
            const cargaSelect = document.getElementById('carga_academica_id');
            const fechaInput = document.getElementById('fecha');
            const loadingSpinner = document.getElementById('loading-cargas');
            const badgePaso2 = document.getElementById('badge-paso2');
            const helpCarga = document.getElementById('help-carga');
            const resumenDiv = document.getElementById('resumen');

            let cargasData = [];

            // Cargar materias cuando se selecciona un docente
            docenteSelect.addEventListener('change', function() {
                const docenteId = this.value;
                const docenteNombre = this.options[this.selectedIndex].text;

                loadingSpinner.classList.remove('d-none');
                badgePaso2.classList.remove('bg-secondary', 'bg-success', 'bg-danger');
                badgePaso2.classList.add('bg-warning');
                badgePaso2.textContent = '2 - Cargando...';

                cargaSelect.innerHTML = '<option value="">⏳ Cargando...</option>';
                cargaSelect.disabled = true;
                cargaSelect.classList.remove('is-valid');
                resumenDiv.style.display = 'none';

                if (!docenteId) {
                    loadingSpinner.classList.add('d-none');
                    badgePaso2.classList.remove('bg-warning');
                    badgePaso2.classList.add('bg-secondary');
                    badgePaso2.textContent = '2';
                    cargaSelect.innerHTML = '<option value="">⬆️ Primero seleccione un docente</option>';
                    helpCarga.innerHTML = '<i class="fa-solid fa-lock"></i> Se habilitará al seleccionar un docente';
                    return;
                }

                const url = '{{ url("admin/habilitaciones/get-materias") }}/' + docenteId;

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.classList.add('d-none');
                    cargasData = data;

                    if (!Array.isArray(data) || data.length === 0) {
                        badgePaso2.classList.remove('bg-warning');
                        badgePaso2.classList.add('bg-danger');
                        badgePaso2.textContent = '2 - Sin datos';
                        cargaSelect.innerHTML = '<option value="">⚠️ Sin asignaciones</option>';
                        helpCarga.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-warning"></i> Este docente no tiene cargas asignadas';
                    } else {
                        badgePaso2.classList.remove('bg-warning');
                        badgePaso2.classList.add('bg-success');
                        badgePaso2.textContent = '2 ✓';

                        cargaSelect.innerHTML = '<option value="">-- Seleccione la clase --</option>';

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
                            option.dataset.descripcion = option.textContent;
                            cargaSelect.appendChild(option);
                        });

                        cargaSelect.disabled = false;
                        helpCarga.innerHTML = '<i class="fa-solid fa-check-circle text-success"></i> ' + data.length + ' clase(s) disponible(s)';
                    }

                    document.getElementById('resumen-docente').textContent = docenteNombre;
                    actualizarResumen();
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.classList.add('d-none');
                    badgePaso2.classList.remove('bg-warning');
                    badgePaso2.classList.add('bg-danger');
                    badgePaso2.textContent = '2 - Error';
                    cargaSelect.innerHTML = '<option value="">❌ Error al cargar</option>';
                    helpCarga.innerHTML = '<i class="fa-solid fa-exclamation-circle text-danger"></i> ' + error.message;
                });
            });

            // Actualizar resumen cuando cambia la carga o fecha
            cargaSelect.addEventListener('change', actualizarResumen);
            fechaInput.addEventListener('change', actualizarResumen);

            function actualizarResumen() {
                const cargaSeleccionada = cargaSelect.value;
                const fechaSeleccionada = fechaInput.value;
                const docenteNombre = document.getElementById('resumen-docente').textContent;

                if (docenteNombre !== '-' && cargaSeleccionada && fechaSeleccionada) {
                    const claseTexto = cargaSelect.options[cargaSelect.selectedIndex].dataset.descripcion;
                    document.getElementById('resumen-clase').textContent = claseTexto;

                    const fechaObj = new Date(fechaSeleccionada + 'T00:00:00');
                    const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    document.getElementById('resumen-fecha').textContent = fechaFormateada;

                    resumenDiv.style.display = 'block';
                } else {
                    resumenDiv.style.display = 'none';
                }
            }
        })();
    </script>
</x-admin-layout>
