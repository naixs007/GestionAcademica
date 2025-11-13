@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-clock"></i> Crear Nuevo Horario</h2>
        <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
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
            <form action="{{ route('admin.horario.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="materia_id" class="form-label">
                            <i class="fa-solid fa-book"></i> Materia <span class="text-danger">*</span>
                        </label>
                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                            <option value="">Seleccione una materia</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->codigo }} - {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('materia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa-solid fa-calendar-day"></i> Días de la Semana <span class="text-danger">*</span>
                        </label>
                        <div class="@error('diaSemana') is-invalid @enderror">
                            @php
                                $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                $oldDias = old('diaSemana', []);
                            @endphp
                            @foreach($dias as $dia)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="diaSemana[]"
                                           value="{{ $dia }}"
                                           id="dia{{ $loop->index }}"
                                           {{ in_array($dia, $oldDias) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dia{{ $loop->index }}">
                                        {{ $dia }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('diaSemana')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Seleccione al menos un día</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="horaInicio" class="form-label">
                            <i class="fa-solid fa-clock"></i> Hora de Inicio <span class="text-danger">*</span>
                        </label>
                        <input type="time"
                               name="horaInicio"
                               id="horaInicio"
                               class="form-control @error('horaInicio') is-invalid @enderror"
                               value="{{ old('horaInicio') }}"
                               required>
                        @error('horaInicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: HH:MM (24 horas)</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="horaFin" class="form-label">
                            <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="time"
                               name="horaFin"
                               id="horaFin"
                               class="form-control @error('horaFin') is-invalid @enderror"
                               value="{{ old('horaFin') }}"
                               required>
                        @error('horaFin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: HH:MM (24 horas)</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="modalidad" class="form-label">
                            <i class="fa-solid fa-chalkboard"></i> Modalidad <span class="text-danger">*</span>
                        </label>
                        <select name="modalidad" id="modalidad" class="form-select @error('modalidad') is-invalid @enderror" required>
                            <option value="">Seleccione modalidad</option>
                            <option value="presencial" {{ old('modalidad') == 'presencial' ? 'selected' : '' }}>
                                <i class="fa-solid fa-building"></i> Presencial
                            </option>
                            <option value="virtual" {{ old('modalidad') == 'virtual' ? 'selected' : '' }}>
                                <i class="fa-solid fa-laptop"></i> Virtual
                            </option>
                        </select>
                        @error('modalidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar Horario
                        </button>
                        <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
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
                <li>La hora de fin debe ser posterior a la hora de inicio.</li>
                <li>Use formato de 24 horas para las horas (ejemplo: 14:30).</li>
                <li>Modalidad <strong>presencial</strong>: La clase se imparte en un aula física.</li>
                <li>Modalidad <strong>virtual</strong>: La clase se imparte mediante plataforma virtual.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
