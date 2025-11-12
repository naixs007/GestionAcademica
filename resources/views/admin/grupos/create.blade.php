<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-plus-circle text-success"></i> Registrar Nuevo Grupo
            </h2>
            <a href="{{ route('admin.grupos.index') }}" class="btn btn-outline-secondary">
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
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-users-rectangle"></i> Información del Grupo
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.grupos.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- Nombre del Grupo --}}
                                <div class="col-md-6 mb-4">
                                    <label for="nombre" class="form-label">
                                        <i class="fa-solid fa-tag text-primary"></i>
                                        <strong>Nombre del Grupo</strong> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="nombre"
                                           id="nombre"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre') }}"
                                           maxlength="100"
                                           placeholder="Ej: Grupo A, Grupo 1, etc."
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fa-solid fa-info-circle"></i>
                                        Identificador único del grupo
                                    </small>
                                </div>

                                {{-- Capacidad --}}
                                <div class="col-md-6 mb-4">
                                    <label for="capacidad" class="form-label">
                                        <i class="fa-solid fa-users text-info"></i>
                                        <strong>Capacidad Máxima</strong> <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           name="capacidad"
                                           id="capacidad"
                                           class="form-control @error('capacidad') is-invalid @enderror"
                                           value="{{ old('capacidad', 30) }}"
                                           min="1"
                                           max="100"
                                           placeholder="Ej: 30"
                                           required>
                                    @error('capacidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fa-solid fa-info-circle"></i>
                                        Número máximo de estudiantes (1-100)
                                    </small>
                                </div>
                            </div>

                            {{-- Información adicional --}}
                            <div class="alert alert-info mb-4">
                                <h6 class="alert-heading">
                                    <i class="fa-solid fa-lightbulb"></i> Información Importante
                                </h6>
                                <ul class="mb-0">
                                    <li>El nombre del grupo debe ser único y descriptivo.</li>
                                    <li>La capacidad se refiere al número máximo de estudiantes que pueden inscribirse.</li>
                                    <li>Las materias se asignan a los grupos desde el módulo de Carga Académica.</li>
                                </ul>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg flex-fill">
                                    <i class="fa-solid fa-save"></i> Registrar Grupo
                                </button>
                                <a href="{{ route('admin.grupos.index') }}" class="btn btn-outline-secondary btn-lg">
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
                        <p class="mb-2"><strong>¿Qué es un grupo?</strong></p>
                        <p class="mb-3">Un grupo es una división de estudiantes dentro de una materia específica. Permite organizar mejor las clases cuando hay muchos estudiantes.</p>

                        <p class="mb-2"><strong>Ejemplos de nombres:</strong></p>
                        <ul class="mb-0">
                            <li><strong>Grupo A, Grupo B:</strong> Para división alfabética</li>
                            <li><strong>Grupo 1, Grupo 2:</strong> Para división numérica</li>
                            <li><strong>Grupo Mañana, Grupo Tarde:</strong> Para división por horario</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
