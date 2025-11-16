@extends('layouts.decano')

@section('title', 'Dashboard Decano')

@section('content')
<div class="container-fluid">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fa-solid fa-gauge text-primary me-2"></i>
                Panel del Decano
            </h2>
            <p class="text-muted mb-0">Bienvenido, {{ auth()->user()->name }}</p>
        </div>
    </div>

    {{-- Tarjetas de Resumen --}}
    <div class="row g-4 mb-4">
        {{-- Docentes --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Docentes</h6>
                            <h3 class="mb-0">{{ \App\Models\Docente::count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fa-solid fa-chalkboard-user fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.docentes.index') }}" class="text-decoration-none text-primary small">
                        Ver todos <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Materias --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Materias</h6>
                            <h3 class="mb-0">{{ \App\Models\Materia::count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fa-solid fa-book fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.materia.index') }}" class="text-decoration-none text-success small">
                        Ver todas <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Grupos --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Grupos Activos</h6>
                            <h3 class="mb-0">{{ \App\Models\Grupo::count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fa-solid fa-users-rectangle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.grupos.index') }}" class="text-decoration-none text-info small">
                        Ver todos <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Asistencias Hoy --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Asistencias Hoy</h6>
                            <h3 class="mb-0">{{ \App\Models\Asistencia::whereDate('fecha', today())->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fa-solid fa-clipboard-check fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.asistencia.index') }}" class="text-decoration-none text-warning small">
                        Ver detalles <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones Rápidas --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-bolt text-warning me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('admin.carga-academica.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fa-solid fa-list-check d-block mb-2 fa-2x"></i>
                                <span>Asignar Carga Académica</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.habilitaciones.index') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fa-solid fa-toggle-on d-block mb-2 fa-2x"></i>
                                <span>Habilitar Marcado</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.reportes.docentes.index') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="fa-solid fa-chart-line d-block mb-2 fa-2x"></i>
                                <span>Ver Reportes</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fa-solid fa-chalkboard-user d-block mb-2 fa-2x"></i>
                                <span>Gestionar Docentes</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.horario.index') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="fa-solid fa-clock d-block mb-2 fa-2x"></i>
                                <span>Gestionar Horarios</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.asistencia.create') }}" class="btn btn-outline-danger w-100 py-3">
                                <i class="fa-solid fa-plus-circle d-block mb-2 fa-2x"></i>
                                <span>Registrar Asistencia</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-info-circle text-info me-2"></i>
                        Información
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="fa-solid fa-user-shield me-2"></i>
                        <strong>Rol:</strong> Decano
                    </div>
                    <div class="alert alert-success mb-3">
                        <i class="fa-solid fa-calendar-day me-2"></i>
                        <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}
                    </div>
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-clock me-2"></i>
                        <strong>Hora:</strong> {{ now()->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
