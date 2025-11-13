<x-admin-layout>
    <div class="container-fluid py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">
                <i class="fa-solid fa-edit text-warning"></i> Editar Configuración del Sistema
            </h2>
            <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa-solid fa-exclamation-circle"></i> Error al actualizar la configuración
                </h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.configuracion.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Formulario Principal --}}
                <div class="col-lg-8">
                    {{-- Información Institucional --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-building"></i> Información Institucional
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Nombre de la Institución --}}
                            <div class="mb-4">
                                <label for="nombre_institucion" class="form-label fw-bold">
                                    <i class="fa-solid fa-school"></i> Nombre de la Institución
                                </label>
                                <input type="text"
                                       class="form-control @error('nombre_institucion') is-invalid @enderror"
                                       id="nombre_institucion"
                                       name="nombre_institucion"
                                       value="{{ old('nombre_institucion', $configuracion->nombre_institucion) }}"
                                       placeholder="Ej: Universidad Autónoma Gabriel René Moreno">
                                @error('nombre_institucion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Nombre completo de la institución educativa.
                                </small>
                            </div>

                            {{-- Logo Institucional --}}
                            <div class="mb-4">
                                <label for="logo_institucional_path" class="form-label fw-bold">
                                    <i class="fa-solid fa-image"></i> Ruta del Logo Institucional
                                </label>
                                <input type="text"
                                       class="form-control @error('logo_institucional_path') is-invalid @enderror"
                                       id="logo_institucional_path"
                                       name="logo_institucional_path"
                                       value="{{ old('logo_institucional_path', $configuracion->logo_institucional_path) }}"
                                       placeholder="/storage/logos/logo.png">
                                @error('logo_institucional_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Ruta del archivo del logo (ej: /storage/logos/logo.png).
                                </small>
                                @if($configuracion->logo_institucional_path)
                                    <div class="mt-2">
                                        <label class="text-muted small">Vista previa actual:</label><br>
                                        <img src="{{ asset($configuracion->logo_institucional_path) }}"
                                             alt="Logo actual"
                                             class="img-thumbnail"
                                             style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            {{-- Período Académico Default --}}
                            <div class="mb-4">
                                <label for="periodo_academico_default_id" class="form-label fw-bold">
                                    <i class="fa-solid fa-calendar-days"></i> Período Académico por Defecto
                                </label>
                                <input type="number"
                                       class="form-control @error('periodo_academico_default_id') is-invalid @enderror"
                                       id="periodo_academico_default_id"
                                       name="periodo_academico_default_id"
                                       value="{{ old('periodo_academico_default_id', $configuracion->periodo_academico_default_id) }}"
                                       placeholder="1">
                                @error('periodo_academico_default_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    ID del período académico que se usará por defecto (opcional).
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Configuración de Asistencia --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-user-check"></i> Configuración de Asistencia
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Tolerancia de Asistencia --}}
                            <div class="mb-4">
                                <label for="tolerancia_asistencia_minutos" class="form-label fw-bold">
                                    <i class="fa-solid fa-clock"></i> Tolerancia de Asistencia (minutos)
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control @error('tolerancia_asistencia_minutos') is-invalid @enderror"
                                           id="tolerancia_asistencia_minutos"
                                           name="tolerancia_asistencia_minutos"
                                           value="{{ old('tolerancia_asistencia_minutos', $configuracion->tolerancia_asistencia_minutos) }}"
                                           min="0"
                                           max="60"
                                           required>
                                    <span class="input-group-text">minutos</span>
                                    @error('tolerancia_asistencia_minutos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Minutos de tolerancia para marcar asistencia (0-60).
                                </small>
                            </div>

                            {{-- Requerir Motivo de Ausencia --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           role="switch"
                                           id="requerir_motivo_ausencia"
                                           name="requerir_motivo_ausencia"
                                           value="1"
                                           {{ old('requerir_motivo_ausencia', $configuracion->requerir_motivo_ausencia) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="requerir_motivo_ausencia">
                                        <i class="fa-solid fa-file-lines"></i> Requerir Motivo de Ausencia
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Si está activado, los estudiantes deberán justificar sus ausencias.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Configuración de Seguridad --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-shield-halved"></i> Configuración de Seguridad
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Expiración de Contraseña --}}
                            <div class="mb-4">
                                <label for="expiracion_contrasena_dias" class="form-label fw-bold">
                                    <i class="fa-solid fa-key"></i> Expiración de Contraseña (días)
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control @error('expiracion_contrasena_dias') is-invalid @enderror"
                                           id="expiracion_contrasena_dias"
                                           name="expiracion_contrasena_dias"
                                           value="{{ old('expiracion_contrasena_dias', $configuracion->expiracion_contrasena_dias) }}"
                                           min="30"
                                           max="365"
                                           required>
                                    <span class="input-group-text">días</span>
                                    @error('expiracion_contrasena_dias')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Días antes de que las contraseñas expiren (30-365).
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Configuración de Notificaciones --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-envelope"></i> Configuración de Notificaciones
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Email Remitente --}}
                            <div class="mb-4">
                                <label for="notificaciones_email_remitente" class="form-label fw-bold">
                                    <i class="fa-solid fa-at"></i> Email Remitente de Notificaciones
                                </label>
                                <input type="email"
                                       class="form-control @error('notificaciones_email_remitente') is-invalid @enderror"
                                       id="notificaciones_email_remitente"
                                       name="notificaciones_email_remitente"
                                       value="{{ old('notificaciones_email_remitente', $configuracion->notificaciones_email_remitente) }}"
                                       placeholder="sistema@universidad.edu">
                                @error('notificaciones_email_remitente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Dirección de correo que aparecerá como remitente en las notificaciones del sistema.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-end gap-2 mb-4">
                        <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>

                {{-- Barra Lateral de Ayuda --}}
                <div class="col-lg-4">
                    {{-- Ayuda --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-lightbulb text-warning"></i> Ayuda
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold">Instrucciones:</h6>
                            <ol class="small mb-0">
                                <li class="mb-2">Complete los campos de información institucional.</li>
                                <li class="mb-2">Configure los parámetros de asistencia según las políticas de su institución.</li>
                                <li class="mb-2">Establezca las políticas de seguridad.</li>
                                <li class="mb-2">Configure el email para notificaciones.</li>
                                <li>Guarde los cambios cuando termine.</li>
                            </ol>
                        </div>
                    </div>

                    {{-- Valores Recomendados --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-star"></i> Valores Recomendados
                            </h6>
                        </div>
                        <div class="card-body small">
                            <p class="mb-2">
                                <strong>Tolerancia de Asistencia:</strong><br>
                                10-15 minutos (estándar académico)
                            </p>
                            <p class="mb-2">
                                <strong>Expiración de Contraseña:</strong><br>
                                90 días (recomendado para seguridad)
                            </p>
                            <p class="mb-0">
                                <strong>Motivo de Ausencia:</strong><br>
                                Activado para mejor control académico
                            </p>
                        </div>
                    </div>

                    {{-- Información del Sistema --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-info-circle"></i> Información
                            </h6>
                        </div>
                        <div class="card-body small">
                            <p class="mb-2">
                                <i class="fa-solid fa-calendar-plus text-muted"></i>
                                <strong>Creado:</strong><br>
                                {{ $configuracion->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-0">
                                <i class="fa-solid fa-calendar-check text-muted"></i>
                                <strong>Última actualización:</strong><br>
                                {{ $configuracion->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
