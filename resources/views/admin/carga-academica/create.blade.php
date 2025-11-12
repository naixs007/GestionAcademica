<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-calendar-plus text-success"></i> Asignar Carga Académica
            </h2>
            <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-circle"></i> Error al asignar carga académica
                </h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Formulario de asignación --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-wpforms"></i> Formulario de Asignación
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.carga-academica.store') }}" method="POST" id="formAsignarCarga">
                            @csrf

                            {{-- Seleccionar Docente --}}
                            <div class="mb-4">
                                <label for="docente_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-chalkboard-user text-primary"></i> Docente <span class="text-danger">*</span>
                                </label>
                                @forelse($docentes as $docente)
                                    @if($loop->first)
                                        <select name="docente_id" id="docente_id" class="form-select @error('docente_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar docente --</option>
                                    @endif
                                    <option value="{{ $docente->id }}" 
                                            data-carga="{{ $docente->cargaHoraria }}"
                                            data-categoria="{{ $docente->categoria }}"
                                            {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->user->name }} - {{ $docente->categoria }} ({{ $docente->cargaHoraria }} hrs/semana)
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i> 
                                        No hay docentes registrados. 
                                        <a href="{{ route('admin.docentes.create') }}" class="alert-link">Registrar docente</a>
                                    </div>
                                @endforelse
                                @error('docente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione el docente al que se le asignará la materia.
                                </small>
                            </div>

                            {{-- Información del Docente Seleccionado --}}
                            <div id="docenteInfo" class="alert alert-info d-none mb-4">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-user"></i> Información del Docente
                                </h6>
                                <p class="mb-1"><strong>Categoría:</strong> <span id="infoCategoria">-</span></p>
                                <p class="mb-0"><strong>Carga Horaria Disponible:</strong> <span id="infoCarga">-</span> hrs/semana</p>
                            </div>

                            {{-- Seleccionar Materia --}}
                            <div class="mb-4">
                                <label for="materia_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-book text-primary"></i> Materia <span class="text-danger">*</span>
                                </label>
                                @forelse($materias as $materia)
                                    @if($loop->first)
                                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar materia --</option>
                                    @endif
                                    <option value="{{ $materia->id }}" 
                                            data-carga="{{ $materia->cargaHoraria }}"
                                            data-codigo="{{ $materia->codigo }}"
                                            data-nivel="{{ $materia->nivel }}"
                                            data-docente="{{ $materia->docente_id }}"
                                            {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                        {{ $materia->codigo }} - {{ $materia->nombre }} 
                                        ({{ $materia->cargaHoraria }} hrs - {{ $materia->nivel }})
                                        @if($materia->docente_id)
                                            <span class="text-danger">[Ya asignada]</span>
                                        @endif
                                    </option>
                                    @if($loop->last)
                                        </select>
                                    @endif
                                @empty
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa-solid fa-exclamation-triangle"></i> 
                                        No hay materias registradas. 
                                        <a href="{{ route('admin.materia.create') }}" class="alert-link">Registrar materia</a>
                                    </div>
                                @endforelse
                                @error('materia_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Seleccione la materia que será impartida por el docente.
                                </small>
                            </div>

                            {{-- Información de la Materia Seleccionada --}}
                            <div id="materiaInfo" class="alert alert-warning d-none mb-4">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-book"></i> Información de la Materia
                                </h6>
                                <p class="mb-1"><strong>Código:</strong> <span id="infoCodigoMateria">-</span></p>
                                <p class="mb-1"><strong>Nivel:</strong> <span id="infoNivelMateria">-</span></p>
                                <p class="mb-0"><strong>Carga Horaria:</strong> <span id="infoCargaMateria">-</span> hrs/semana</p>
                                <div id="alertaMateriaAsignada" class="alert alert-danger mt-2 d-none">
                                    <i class="fa-solid fa-exclamation-triangle"></i> Esta materia ya está asignada a otro docente. La asignación se reemplazará.
                                </div>
                            </div>

                            {{-- Seleccionar Grupo (opcional) --}}
                            <div class="mb-4">
                                <label for="grupo_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-users-rectangle text-primary"></i> Grupo (Opcional)
                                </label>
                                @if($grupos->count() > 0)
                                    <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror">
                                        <option value="">-- Sin asignar a grupo específico --</option>
                                        @foreach($grupos as $grupo)
                                            <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                                {{ $grupo->nombre }} - {{ $grupo->materias->nombre ?? 'Sin materia' }} (Cap: {{ $grupo->capacidad }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="No hay grupos registrados" disabled>
                                @endif
                                @error('grupo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i> Opcional: Puede asignar la materia a un grupo específico.
                                </small>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success" id="btnSubmit">
                                    <i class="fa-solid fa-save"></i> Asignar Carga Académica
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="col-lg-4">
                {{-- Card de ayuda --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-lightbulb"></i> Instrucciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0 ps-3">
                            <li class="mb-2">Seleccione el <strong>docente</strong> que impartirá la materia.</li>
                            <li class="mb-2">Elija la <strong>materia</strong> a asignar.</li>
                            <li class="mb-2">Opcionalmente, asocie un <strong>grupo</strong> específico.</li>
                            <li>Verifique que la carga horaria no exceda el límite del docente.</li>
                        </ol>
                    </div>
                </div>

                {{-- Card de estadísticas --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-chart-bar"></i> Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Docentes Registrados</label>
                            <h4 class="mb-0 text-primary">{{ $docentes->count() }}</h4>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Materias Disponibles</label>
                            <h4 class="mb-0 text-success">{{ $materias->count() }}</h4>
                        </div>
                        <div>
                            <label class="text-muted small">Grupos Registrados</label>
                            <h4 class="mb-0 text-info">{{ $grupos->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const docenteSelect = document.getElementById('docente_id');
            const materiaSelect = document.getElementById('materia_id');
            const docenteInfo = document.getElementById('docenteInfo');
            const materiaInfo = document.getElementById('materiaInfo');
            const btnSubmit = document.getElementById('btnSubmit');

            // Mostrar información del docente seleccionado
            if(docenteSelect) {
                docenteSelect.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if(this.value) {
                        document.getElementById('infoCategoria').textContent = option.dataset.categoria;
                        document.getElementById('infoCarga').textContent = option.dataset.carga;
                        docenteInfo.classList.remove('d-none');
                    } else {
                        docenteInfo.classList.add('d-none');
                    }
                });
            }

            // Mostrar información de la materia seleccionada
            if(materiaSelect) {
                materiaSelect.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if(this.value) {
                        document.getElementById('infoCodigoMateria').textContent = option.dataset.codigo;
                        document.getElementById('infoNivelMateria').textContent = option.dataset.nivel;
                        document.getElementById('infoCargaMateria').textContent = option.dataset.carga;
                        
                        // Mostrar alerta si la materia ya está asignada
                        const alertaMateriaAsignada = document.getElementById('alertaMateriaAsignada');
                        if(option.dataset.docente) {
                            alertaMateriaAsignada.classList.remove('d-none');
                        } else {
                            alertaMateriaAsignada.classList.add('d-none');
                        }
                        
                        materiaInfo.classList.remove('d-none');
                    } else {
                        materiaInfo.classList.add('d-none');
                    }
                });
            }
        });
    </script>
</x-admin-layout>
