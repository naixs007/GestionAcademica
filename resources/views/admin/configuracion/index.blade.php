<x-admin-layout>
    <div class="container-fluid py-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-gear"></i> Configurar Parámetros Generales</h2>
        <a href="{{ route('admin.configuracion.edit') }}" class="btn btn-primary">
            <i class="fa-solid fa-edit"></i> Editar Configuración
        </a>
    </div>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Información Institucional --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-building"></i> Información Institucional
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="text-muted small">Nombre de la Institución</label>
                            </div>
                            <div class="col-md-8">
                                <strong>{{ $configuracion->nombre_institucion ?? 'No configurado' }}</strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="text-muted small">Logo Institucional</label>
                            </div>
                            <div class="col-md-8">
                                @if($configuracion->logo_institucional_path)
                                    <img src="{{ asset($configuracion->logo_institucional_path) }}"
                                         alt="Logo"
                                         class="img-thumbnail"
                                         style="max-height: 80px;">
                                @else
                                    <span class="badge bg-secondary">Sin logo configurado</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-muted small">Período Académico Default</label>
                            </div>
                            <div class="col-md-8">
                                @if($configuracion->periodo_academico_default_id)
                                    <span class="badge bg-info">ID: {{ $configuracion->periodo_academico_default_id }}</span>
                                @else
                                    <span class="badge bg-secondary">No configurado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuración de Asistencia --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-check"></i> Configuración de Asistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Tolerancia de Asistencia</label>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success fs-5 me-2">
                                        {{ $configuracion->tolerancia_asistencia_minutos }}
                                    </span>
                                    <span>minutos</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Requerir Motivo de Ausencia</label>
                            </div>
                            <div class="col-md-6">
                                @if($configuracion->requerir_motivo_ausencia)
                                    <span class="badge bg-warning">
                                        <i class="fa-solid fa-check"></i> Sí, es requerido
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fa-solid fa-times"></i> No requerido
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuración de Seguridad --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-shield-halved"></i> Configuración de Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Expiración de Contraseña</label>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning text-dark fs-5 me-2">
                                        {{ $configuracion->expiracion_contrasena_dias }}
                                    </span>
                                    <span>días</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuración de Notificaciones --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-envelope"></i> Configuración de Notificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Email Remitente</label>
                            </div>
                            <div class="col-md-6">
                                @if($configuracion->notificaciones_email_remitente)
                                    <a href="mailto:{{ $configuracion->notificaciones_email_remitente }}"
                                       class="text-decoration-none">
                                        <i class="fa-solid fa-envelope"></i>
                                        {{ $configuracion->notificaciones_email_remitente }}
                                    </a>
                                @else
                                    <span class="badge bg-secondary">No configurado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del Sistema --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-database"></i> Información del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small d-block">
                                    <i class="fa-solid fa-calendar-plus"></i> Fecha de Creación
                                </label>
                                <strong>{{ $configuracion->created_at->format('d/m/Y H:i:s') }}</strong>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small d-block">
                                    <i class="fa-solid fa-calendar-check"></i> Última Actualización
                                </label>
                                <strong>{{ $configuracion->updated_at->format('d/m/Y H:i:s') }}</strong>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small d-block">
                                    <i class="fa-solid fa-hashtag"></i> ID de Configuración
                                </label>
                                <strong>#{{ $configuracion->id }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones Adicionales --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-triangle-exclamation"></i> Zona de Peligro
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Restablecer Configuración</h6>
                                    <p class="text-muted mb-0 small">
                                        Restaurar todos los parámetros a sus valores predeterminados.
                                    </p>
                                </div>
                                <form action="{{ route('admin.configuracion.reset') }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Está seguro de restablecer la configuración a valores por defecto? Esta acción no se puede deshacer.');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-rotate-left"></i> Restablecer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
