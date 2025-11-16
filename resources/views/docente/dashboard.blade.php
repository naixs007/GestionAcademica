@extends('layouts.docente')

@section('title', 'Dashboard Docente')

@section('content')<div class="container-fluid py-4">
    <h2 class="mb-4">
        <i class="fa-solid fa-gauge"></i> Bienvenido, {{ auth()->user()->name }}
    </h2>

    <div class="row">
        {{-- Tarjeta Mi Asistencia --}}
        <div class="col-md-4 mb-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="fa-solid fa-clipboard-check fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Mi Asistencia</h5>
                    <p class="card-text">Consulta tu historial de asistencias y marca tu presencia.</p>
                    <a href="{{ route('docente.asistencia.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-arrow-right"></i> Ver Asistencias
                    </a>
                </div>
            </div>
        </div>

        {{-- Tarjeta Marcar Asistencia --}}
        <div class="col-md-4 mb-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fa-solid fa-hand-pointer fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Marcar Asistencia</h5>
                    <p class="card-text">Registra tu asistencia para tus clases de hoy.</p>
                    <a href="{{ route('docente.asistencia.marcar') }}" class="btn btn-success">
                        <i class="fa-solid fa-check"></i> Marcar Ahora
                    </a>
                </div>
            </div>
        </div>

        {{-- Tarjeta Mi Carga Académica --}}
        <div class="col-md-4 mb-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="fa-solid fa-book fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Mi Carga Académica</h5>
                    <p class="card-text">Consulta tus materias, horarios y grupos asignados.</p>
                    <a href="{{ route('docente.carga-academica') }}" class="btn btn-info">
                        <i class="fa-solid fa-eye"></i> Ver Carga
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Información Rápida --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-info-circle"></i> Información Importante</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Puedes marcar tu asistencia desde <strong>15 minutos antes</strong> hasta <strong>15 minutos después</strong> de la hora de inicio de tu clase.</li>
                        <li>Recuerda marcar tu asistencia a tiempo para evitar ser marcado como ausente.</li>
                        <li>Si tienes algún problema con el registro, contacta al administrador del sistema.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
