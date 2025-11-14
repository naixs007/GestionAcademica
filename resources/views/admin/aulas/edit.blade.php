<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-edit"></i> Editar Aula/Laboratorio</h2>
            <a href="{{ route('admin.aula.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.aula.update', $aula) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">
                                <i class="fa-solid fa-signature"></i> Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="codigo" id="codigo"
                                class="form-control @error('codigo') is-invalid @enderror"
                                value="{{ old('codigo', $aula->codigo) }}" required maxlength="100">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="tipo" class="form-label">
                                <i class="fa-solid fa-tags"></i> Tipo <span class="text-danger">*</span>
                            </label>
                            <select name="tipo" id="tipo"
                                class="form-select @error('tipo') is-invalid @enderror" required>
                                <option value="">Seleccione tipo</option>
                                <option value="Presencial" {{ old('tipo', $aula->tipo) == 'Presencial' ? 'selected' : '' }}>
                                    Presencial
                                </option>
                                <option value="Laboratorio"
                                    {{ old('tipo', $aula->tipo) == 'Laboratorio' ? 'selected' : '' }}>
                                    Laboratorio
                                </option>
                                <option value="Virtual"
                                    {{ old('tipo', $aula->tipo) == 'Virtual' ? 'selected' : '' }}>
                                    Virtual
                                </option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="capacidad" class="form-label">
                                <i class="fa-solid fa-users"></i> Capacidad <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="capacidad" id="capacidad"
                                class="form-control @error('capacidad') is-invalid @enderror"
                                value="{{ old('capacidad', $aula->capacidad) }}" required min="1" max="200">
                            @error('capacidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Personas (1-200)</small>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Actualizar Aula
                            </button>
                            <a href="{{ route('admin.aula.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
