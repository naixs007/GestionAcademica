@extends('layouts.docente')

@section('title', 'Marcar Asistencia')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fa-solid fa-hand-pointer"></i> Marcar Mi Asistencia
        </h2>
        <a href="{{ route('docente.asistencia.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Información --}}
    <div class="alert alert-info">
        <i class="fa-solid fa-info-circle"></i>
        <strong>Ventana de Marcado:</strong> Puedes marcar tu asistencia desde <strong>15 minutos antes</strong> hasta <strong>15 minutos después</strong> de la hora de inicio de tu clase.
    </div>

    {{-- Mis Clases de Hoy --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-calendar-day"></i> Mis Clases de Hoy - {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </h5>
        </div>
        <div class="card-body">
            @if($cargasHoy->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-calendar-xmark fs-1 d-block mb-2"></i>
                    <p class="mb-0">No tienes clases programadas para hoy.</p>
                </div>
            @else
                <div class="row">
                    @foreach($cargasHoy as $carga)
                        @php
                            $ahora = \Carbon\Carbon::now();
                            $horaInicio = \Carbon\Carbon::parse($carga->horario->hora_inicio);
                            $horaApertura = $horaInicio->copy()->subMinutes(15);
                            $horaCierre = $horaInicio->copy()->addMinutes(15);

                            // MODO PRUEBA: Siempre permitir marcar si hay habilitación (para testing)
                            $esVentanaActiva = true; // Cambiar a: $ahora->between($horaApertura, $horaCierre); en producción
                            $esVentanaFutura = false;

                            // Verificar si ya marcó
                            $yaMarcado = \App\Models\Asistencia::where('docente_id', $docente->id)
                                ->where('materia_id', $carga->materia_id)
                                ->where('grupo_id', $carga->grupo_id)
                                ->where('horario_id', $carga->horario_id)
                                ->where('fecha', \Carbon\Carbon::now()->format('Y-m-d'))
                                ->exists();
                        @endphp

                        <div class="col-md-6 mb-3">
                            <div class="card border-{{ $esVentanaActiva ? 'success' : ($esVentanaFutura ? 'warning' : 'danger') }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $carga->materia->nombre }}</h5>
                                    <p class="mb-2"><strong>Código:</strong> {{ $carga->materia->codigo }}</p>
                                    <p class="mb-2"><strong>Grupo:</strong> {{ $carga->grupo->nombre }}</p>
                                    <p class="mb-2"><strong>Horario:</strong> {{ $carga->horario->hora_inicio }} - {{ $carga->horario->hora_fin }}</p>
                                    <p class="mb-2"><strong>Aula:</strong> {{ $carga->aula ? $carga->aula->nombre : 'No asignada' }}</p>

                                    <hr>

                                    @if($yaMarcado)
                                        <div class="alert alert-success mb-0">
                                            <i class="fa-solid fa-check-circle"></i> Ya marcaste tu asistencia para esta clase.
                                        </div>
                                    @elseif($esVentanaActiva)
                                        <div class="alert alert-warning mb-2">
                                            <i class="fa-solid fa-flask"></i> <strong>MODO PRUEBA:</strong> Ventana de tiempo deshabilitada para testing
                                        </div>
                                        <div class="alert alert-info mb-2">
                                            <i class="fa-solid fa-shield-check"></i> <strong>Habilitación activa:</strong> El administrador ha habilitado el marcado para esta clase.
                                        </div>
                                        <button type="button"
                                                class="btn btn-success btn-marcar"
                                                data-carga-id="{{ $carga->id }}"
                                                data-habilitacion-id="{{ $carga->habilitacion_id }}"
                                                data-materia="{{ $carga->materia->nombre }}">
                                            <i class="fa-solid fa-hand-pointer"></i> Marcar Asistencia
                                        </button>
                                    @elseif($esVentanaFutura)
                                        <div class="alert alert-warning mb-0">
                                            <i class="fa-solid fa-hourglass-half"></i> La ventana abre a las {{ $horaApertura->format('H:i') }}
                                        </div>
                                    @else
                                        <div class="alert alert-danger mb-0">
                                            <i class="fa-solid fa-times-circle"></i> Ventana cerrada - Cerró a las {{ $horaCierre->format('H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const botonesMarcar = document.querySelectorAll('.btn-marcar');

    botonesMarcar.forEach(boton => {
        boton.addEventListener('click', function() {
            const cargaId = this.getAttribute('data-carga-id');
            const habilitacionId = this.getAttribute('data-habilitacion-id');
            const materia = this.getAttribute('data-materia');

            // Mostrar modal para confirmar con contraseña
            Swal.fire({
                title: 'Confirmar Asistencia',
                html: `
                    <p class="mb-3"><strong>Materia:</strong> ${materia}</p>
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-shield-check"></i>
                        Para confirmar tu asistencia, ingresa tu contraseña
                    </div>
                    <input type="password"
                           id="password-confirm"
                           class="swal2-input"
                           placeholder="Tu contraseña"
                           autocomplete="current-password">
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                preConfirm: () => {
                    const password = document.getElementById('password-confirm').value;
                    if (!password) {
                        Swal.showValidationMessage('Debes ingresar tu contraseña');
                        return false;
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    marcarAsistencia(cargaId, habilitacionId, result.value);
                }
            });
        });
    });

    function marcarAsistencia(cargaId, habilitacionId, password) {
        Swal.fire({
            title: 'Registrando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("docente.asistencia.procesar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                carga_academica_id: cargaId,
                habilitacion_id: habilitacionId,
                password: password
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Error en la petición');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Asistencia Marcada!',
                    html: `
                        <p>${data.message}</p>
                        ${data.data.hora_llegada ? `<p class="text-muted">Hora de llegada: <strong>${data.data.hora_llegada}</strong></p>` : ''}
                    `,
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No se pudo conectar con el servidor.',
                confirmButtonColor: '#dc3545'
            });
        });
    }
});
</script>
@endpush
@endsection
