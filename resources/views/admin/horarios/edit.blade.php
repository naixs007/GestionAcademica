@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-edit"></i> Editar Horario</h2>
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
            <form action="{{ route('admin.horario.update', $horario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="materia_id" class="form-label">
                            <i class="fa-solid fa-book"></i> Materia <span class="text-danger">*</span>
                        </label>
                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                            <option value="">Seleccione una materia</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ (old('materia_id', $horario->materia_id) == $materia->id) ? 'selected' : '' }}>
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
                                $diasSeleccionados = old('diaSemana', is_array($horario->diaSemana) ? $horario->diaSemana : [$horario->diaSemana]);
                            @endphp
                            @foreach($dias as $dia)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="diaSemana[]"
                                           value="{{ $dia }}"
                                           id="dia{{ $loop->index }}"
                                           {{ in_array($dia, $diasSeleccionados) ? 'checked' : '' }}>
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
                               value="{{ old('horaInicio', \Carbon\Carbon::parse($horario->horaInicio)->format('H:i')) }}"
                               required>
                        @error('horaInicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="horaFin" class="form-label">
                            <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="time"
                               name="horaFin"
                               id="horaFin"
                               class="form-control @error('horaFin') is-invalid @enderror"
                               value="{{ old('horaFin', \Carbon\Carbon::parse($horario->horaFin)->format('H:i')) }}"
                               required>
                        @error('horaFin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="modalidad" class="form-label">
                            <i class="fa-solid fa-chalkboard"></i> Modalidad <span class="text-danger">*</span>
                        </label>
                        <select name="modalidad" id="modalidad" class="form-select @error('modalidad') is-invalid @enderror" required>
                            <option value="">Seleccione modalidad</option>
                            <option value="presencial" {{ old('modalidad', $horario->modalidad) == 'presencial' ? 'selected' : '' }}>
                                Presencial
                            </option>
                            <option value="virtual" {{ old('modalidad', $horario->modalidad) == 'virtual' ? 'selected' : '' }}>
                                Virtual
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
                            <i class="fa-solid fa-save"></i> Actualizar Horario
                        </button>
                        <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
