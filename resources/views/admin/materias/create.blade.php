<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-plus-circle text-success"></i> Registrar Nueva Materia
            </h2>
            <a href="{{ route('admin.materia.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-triangle"></i> Errores de validación
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
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book"></i> Información de la Materia
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.materia.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- Columna izquierda --}}
                                <div class="col-md-6">
                                    {{-- Código --}}
                                    <div class="mb-4">
                                        <label for="codigo" class="form-label">
                                            <i class="fa-solid fa-barcode text-primary"></i>
                                            <strong>Código/Sigla de la Materia</strong> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="codigo"
                                               id="codigo"
                                               class="form-control @error('codigo') is-invalid @enderror"
                                               value="{{ old('codigo') }}"
                                               maxlength="20"
                                               placeholder="Ej: MAT-101"
                                               required>
                                        <input type="hidden" name="sigla" id="sigla" value="{{ old('codigo') }}">
                                        @error('codigo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i>
                                            Código único para identificar la materia
                                        </small>
                                    </div>

                                    {{-- Nombre --}}
                                    <div class="mb-4">
                                        <label for="nombre" class="form-label">
                                            <i class="fa-solid fa-book-open text-info"></i>
                                            <strong>Nombre de la Materia</strong> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nombre"
                                               id="nombre"
                                               class="form-control @error('nombre') is-invalid @enderror"
                                               value="{{ old('nombre') }}"
                                               maxlength="150"
                                               placeholder="Ej: Programación I"
                                               required>
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i>
                                            Nombre completo de la materia
                                        </small>
                                    </div>

                                    {{-- Nivel --}}
                                    <div class="mb-4">
                                        <label for="nivel" class="form-label">
                                            <i class="fa-solid fa-layer-group text-warning"></i>
                                            <strong>Nivel</strong> <span class="text-danger">*</span>
                                        </label>
                                        <select name="nivel" id="nivel"
                                                class="form-select @error('nivel') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione un nivel</option>
                                            <option value="1" {{ old('nivel') == '1' ? 'selected' : '' }}>
                                                1er Semestre
                                            </option>
                                            <option value="2" {{ old('nivel') == '2' ? 'selected' : '' }}>
                                                2do Semestre
                                            </option>
                                            <option value="3" {{ old('nivel') == '3' ? 'selected' : '' }}>
                                                3er Semestre
                                            </option>
                                            <option value="4" {{ old('nivel') == '4' ? 'selected' : '' }}>
                                                4to Semestre
                                            </option>
                                            <option value="5" {{ old('nivel') == '5' ? 'selected' : '' }}>
                                                5to Semestre
                                            </option>
                                            <option value="6" {{ old('nivel') == '6' ? 'selected' : '' }}>
                                                6to Semestre
                                            </option>
                                            <option value="7" {{ old('nivel') == '7' ? 'selected' : '' }}>
                                                7mo Semestre
                                            </option>
                                            <option value="8" {{ old('nivel') == '8' ? 'selected' : '' }}>
                                                8vo Semestre
                                            </option>
                                            <option value="9" {{ old('nivel') == '9' ? 'selected' : '' }}>
                                                9no Semestre
                                            </option>
                                            <option value="10" {{ old('nivel') == '10' ? 'selected' : '' }}>
                                                10mo Semestre
                                            </option>
                                        </select>
                                        @error('nivel')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i>
                                            Semestre en el que se imparte la materia
                                        </small>
                                    </div>
                                </div>

                                {{-- Columna derecha --}}
                                <div class="col-md-6">
                                    {{-- Carga Horaria --}}
                                    <div class="mb-4">
                                        <label for="cargaHoraria" class="form-label">
                                            <i class="fa-solid fa-clock text-success"></i>
                                            <strong>Carga Horaria (horas/semana)</strong> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="cargaHoraria"
                                               id="cargaHoraria"
                                               class="form-control @error('cargaHoraria') is-invalid @enderror"
                                               value="{{ old('cargaHoraria', 4.5) }}"
                                               min="1"
                                               max="20"
                                               step="0.01"
                                               placeholder="Ej: 4.50"
                                               required>
                                        @error('cargaHoraria')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle"></i>
                                            Horas semanales de clase (1-24)
                                        </small>
                                    </div>

                                    {{-- Información adicional --}}
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">
                                            <i class="fa-solid fa-lightbulb"></i> Tips
                                        </h6>
                                        <ul class="mb-0 small">
                                            <li>El código debe ser único para cada materia</li>
                                            <li>La carga horaria típica es de 2 a 6 horas semanales</li>
                                            <li>Los docentes se asignan desde el módulo de Carga Académica</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-success btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Registrar Materia
                                </button>
                                <a href="{{ route('admin.materia.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tarjeta de ayuda --}}
                <div class="card mt-4 border-primary">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa-solid fa-question-circle text-primary"></i> Ayuda
                        </h6>
                        <p class="mb-2"><strong>Estructura del Código:</strong></p>
                        <p>Se recomienda usar un formato estándar como:</p>
                        <ul class="mb-3">
                            <li><strong>MAT-101:</strong> Materia básica del primer semestre</li>
                            <li><strong>FIS-201:</strong> Física del segundo nivel</li>
                            <li><strong>INF-405:</strong> Informática del cuarto nivel</li>
                        </ul>
                        <p class="mb-0 text-muted small">
                            <i class="fa-solid fa-info-circle"></i>
                            Esto ayuda a organizar mejor el plan de estudios.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sincronizar código con sigla automáticamente
        document.getElementById('codigo').addEventListener('input', function() {
            document.getElementById('sigla').value = this.value;
        });
    </script>
</x-admin-layout>
