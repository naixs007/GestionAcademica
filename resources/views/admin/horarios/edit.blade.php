<x-admin-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-edit"></i> Editar Horario</h2>
            <a href="{{ route('admin.horario.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.horario.update', $horario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fa-solid fa-calendar-day"></i> Días de la Semana <span class="text-danger">*</span>
                            </label>
                            <div class="@error('dias_semana') is-invalid @enderror">
                                @php
                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                    $oldDias = old('dias_semana', $horario->dias_relacionados ?? [$horario->dia_semana]);
                                @endphp
                                @foreach ($dias as $dia)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_semana[]"
                                            value="{{ $dia }}" id="dia{{ $loop->index }}"
                                            {{ in_array($dia, $oldDias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dia{{ $loop->index }}">
                                            {{ $dia }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('dias_semana')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Puede seleccionar múltiples días para aplicar el mismo horario</small>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="hora_inicio" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                class="form-control @error('hora_inicio') is-invalid @enderror"
                                value="{{ old('hora_inicio', substr($horario->hora_inicio, 0, 5)) }}"
                                required>
                            @error('hora_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="hora_fin" class="form-label">
                                <i class="fa-solid fa-clock"></i> Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="hora_fin" id="hora_fin"
                                class="form-control @error('hora_fin') is-invalid @enderror"
                                value="{{ old('hora_fin', substr($horario->hora_fin, 0, 5)) }}"
                                required>
                            @error('hora_fin')
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
</x-admin-layout>
