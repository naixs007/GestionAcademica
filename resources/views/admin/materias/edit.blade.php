<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-edit text-warning"></i> Editar Materia
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
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-book"></i> Actualizar Información de la Materia
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.materia.update', $materia) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Columna Izquierda --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-info-circle"></i> Datos Básicos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            {{-- Código --}}
                                            <div class="mb-3">
                                                <label for="codigo" class="form-label">
                                                    <i class="fa-solid fa-barcode text-primary"></i>
                                                    <strong>Código/Sigla</strong> <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       name="codigo"
                                                       id="codigo"
                                                       class="form-control @error('codigo') is-invalid @enderror"
                                                       value="{{ old('codigo', $materia->codigo) }}"
                                                       maxlength="20"
                                                       required>
                                                <input type="hidden" name="sigla" id="sigla" value="{{ old('codigo', $materia->codigo) }}">
                                                @error('codigo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Nombre --}}
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">
                                                    <i class="fa-solid fa-book-open text-info"></i>
                                                    <strong>Nombre</strong> <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       name="nombre"
                                                       id="nombre"
                                                       class="form-control @error('nombre') is-invalid @enderror"
                                                       value="{{ old('nombre', $materia->nombre) }}"
                                                       maxlength="150"
                                                       required>
                                                @error('nombre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Nivel --}}
                                            <div class="mb-3">
                                                <label for="nivel" class="form-label">
                                                    <i class="fa-solid fa-layer-group text-warning"></i>
                                                    <strong>Nivel</strong> <span class="text-danger">*</span>
                                                </label>
                                                <select name="nivel" id="nivel"
                                                        class="form-select @error('nivel') is-invalid @enderror"
                                                        required>
                                                    <option value="">Seleccione un nivel</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        @php
                                                            $nivelTexto = match($i) {
                                                                1 => '1er Semestre',
                                                                2 => '2do Semestre',
                                                                3 => '3er Semestre',
                                                                9 => '9no Semestre',
                                                                10 => '10mo Semestre',
                                                                default => $i . 'to Semestre'
                                                            };
                                                        @endphp
                                                        <option value="{{ $i }}"
                                                                {{ old('nivel', $materia->nivel) == $i ? 'selected' : '' }}>
                                                            {{ $nivelTexto }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('nivel')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Columna Derecha --}}
                                <div class="col-md-6">
                                    <div class="card mb-4 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-chalkboard-user"></i> Asignación
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            {{-- Carga Horaria --}}
                                            <div class="mb-3">
                                                <label for="cargaHoraria" class="form-label">
                                                    <i class="fa-solid fa-clock text-success"></i>
                                                    <strong>Carga Horaria</strong> <span class="text-danger">*</span>
                                                </label>
                                                <input type="number"
                                                       name="cargaHoraria"
                                                       id="cargaHoraria"
                                                       class="form-control @error('cargaHoraria') is-invalid @enderror"
                                                       value="{{ old('cargaHoraria', $materia->cargaHoraria) }}"
                                                       min="1"
                                                       max="20"
                                                       required>
                                                @error('cargaHoraria')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                {{-- Barra de progreso --}}
                                                <div class="progress mt-2" style="height: 20px;">
                                                    <div class="progress-bar bg-success"
                                                         role="progressbar"
                                                         style="width: {{ ($materia->cargaHoraria / 20) * 100 }}%"
                                                         aria-valuenow="{{ $materia->cargaHoraria }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="20">
                                                        {{ $materia->cargaHoraria }} hrs
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Estadísticas --}}
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-chart-bar"></i> Estadísticas
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-users-rectangle text-primary"></i> Asignaciones:
                                                </span>
                                                <strong>{{ $materia->cargasAcademicas->count() }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-calendar text-warning"></i> Horarios:
                                                </span>
                                                <strong>{{ $materia->horarios->count() }}</strong>
                                            </div>
                                                </span>
                                                <strong>{{ $materia->grupos->count() }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>
                                                    <i class="fa-solid fa-calendar text-warning"></i> Horarios:
                                                </span>
                                                <strong>{{ $materia->horarios->count() }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class="fa-solid fa-clock text-success"></i> Creada:
                                                </span>
                                                <strong>{{ $materia->created_at->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Advertencia --}}
                            <div class="alert alert-warning mb-4">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <strong>Importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>El cambio de código puede afectar los registros históricos.</li>
                                    <li>Los docentes se asignan desde el módulo de Carga Académica.</li>
                                    <li>No se puede eliminar la materia si tiene asignaciones activas.</li>
                                </ul>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Actualizar Materia
                                </button>
                                <a href="{{ route('admin.materia.show', $materia) }}"
                                   class="btn btn-info btn-lg">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('admin.materia.index') }}"
                                   class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Información del sistema --}}
                <div class="card mt-4 border-secondary">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa-solid fa-info-circle text-secondary"></i> Información del Sistema
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">ID Materia:</small>
                                <p><strong>#{{ $materia->id }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Creada:</small>
                                <p><strong>{{ $materia->created_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Última actualización:</small>
                                <p><strong>{{ $materia->updated_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                        </div>
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

        // Actualizar la barra de progreso cuando cambie la carga horaria
        document.getElementById('cargaHoraria').addEventListener('input', function() {
            const value = this.value;
            const progressBar = document.querySelector('.progress-bar');
            const percentage = (value / 20) * 100;
            progressBar.style.width = percentage + '%';
            progressBar.textContent = value + ' hrs';
        });
    </script>
</x-admin-layout>
