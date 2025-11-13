@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-door-open"></i> Crear Nueva Aula/Laboratorio</h2>
        <a href="{{ route('admin.aula.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-circle"></i> <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.aula.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">
                            <i class="fa-solid fa-signature"></i> Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nombre"
                               id="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}"
                               placeholder="Ej: Aula 101, Lab. Física"
                               required
                               maxlength="100">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 100 caracteres</small>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="tipo" class="form-label">
                            <i class="fa-solid fa-tags"></i> Tipo <span class="text-danger">*</span>
                        </label>
                        <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="">Seleccione tipo</option>
                            <option value="aula" {{ old('tipo') == 'aula' ? 'selected' : '' }}>
                                <i class="fa-solid fa-chalkboard"></i> Aula
                            </option>
                            <option value="laboratorio" {{ old('tipo') == 'laboratorio' ? 'selected' : '' }}>
                                <i class="fa-solid fa-flask"></i> Laboratorio
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
                        <input type="number"
                               name="capacidad"
                               id="capacidad"
                               class="form-control @error('capacidad') is-invalid @enderror"
                               value="{{ old('capacidad') }}"
                               placeholder="Ej: 30"
                               required
                               min="1"
                               max="200">
                        @error('capacidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Personas (1-200)</small>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar Aula
                        </button>
                        <a href="{{ route('admin.aula.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            <i class="fa-solid fa-info-circle"></i> Información
        </div>
        <div class="card-body">
            <ul>
                <li>Todos los campos marcados con <span class="text-danger">*</span> son obligatorios.</li>
                <li>El nombre debe ser único, no puede duplicarse.</li>
                <li><strong>Tipo Aula:</strong> Espacio físico para clases teóricas.</li>
                <li><strong>Tipo Laboratorio:</strong> Espacio equipado para clases prácticas y experimentales.</li>
                <li>La capacidad indica el número máximo de personas que puede albergar el espacio.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
