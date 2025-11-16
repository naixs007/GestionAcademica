<x-admin-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Reportes de Docentes
            </h2>
        </div>

        {{-- Mensajes --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Formulario de Personalización --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-filter"></i> Personalizar Reporte
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reportes.docentes.index') }}" method="GET" id="formReporte">
                    <div class="row">
                        {{-- Seleccionar Docente --}}
                        <div class="col-md-6 mb-3">
                            <label for="docente_id" class="form-label">
                                <i class="fa-solid fa-user-tie"></i> Docente <span class="text-danger">*</span>
                            </label>
                            <select name="docente_id" id="docente_id" class="form-select" required>
                                <option value="">-- Seleccione un docente --</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rango de Fechas --}}
                        <div class="col-md-3 mb-3">
                            <label for="fecha_inicio" class="form-label">
                                <i class="fa-solid fa-calendar-day"></i> Fecha Inicio
                            </label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio"
                                   class="form-control"
                                   value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="fecha_fin" class="form-label">
                                <i class="fa-solid fa-calendar-day"></i> Fecha Fin
                            </label>
                            <input type="date" name="fecha_fin" id="fecha_fin"
                                   class="form-control"
                                   value="{{ request('fecha_fin', now()->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Opciones de Inclusión --}}
                    <div class="mb-3">
                        <label class="form-label d-block">
                            <i class="fa-solid fa-list-check"></i> Información a Incluir
                        </label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="incluir[]"
                                           value="cargas" id="incluir_cargas"
                                           {{ in_array('cargas', request('incluir', ['cargas', 'asistencias'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="incluir_cargas">
                                        <i class="fa-solid fa-book"></i> Cargas Académicas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="incluir[]"
                                           value="asistencias" id="incluir_asistencias"
                                           {{ in_array('asistencias', request('incluir', ['cargas', 'asistencias'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="incluir_asistencias">
                                        <i class="fa-solid fa-calendar-check"></i> Asistencias
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="incluir[]"
                                           value="habilitaciones" id="incluir_habilitaciones"
                                           {{ in_array('habilitaciones', request('incluir', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="incluir_habilitaciones">
                                        <i class="fa-solid fa-toggle-on"></i> Habilitaciones
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass-chart"></i> Generar Reporte
                        </button>
                        @if($reporte)
                            <button type="button" class="btn btn-danger" onclick="descargarPDF()">
                                <i class="fa-solid fa-file-pdf"></i> Descargar PDF
                            </button>
                        @endif
                        <a href="{{ route('admin.reportes.docentes.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-broom"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Mostrar Reporte --}}
        @if($reporte)
            {{-- Información Básica --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-user"></i> Información del Docente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Nombre Completo:</th>
                                    <td><strong>{{ $reporte['info_basica']['nombre'] }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $reporte['info_basica']['email'] }}</td>
                                </tr>
                                <tr>
                                    <th>Especialidad:</th>
                                    <td>{{ $reporte['info_basica']['especialidad'] ?? 'No especificada' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Grado Académico:</th>
                                    <td>{{ $reporte['info_basica']['grado_academico'] ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{ $reporte['info_basica']['telefono'] ?? 'No registrado' }}</td>
                                </tr>
                                <tr>
                                    <th>Carga Máxima:</th>
                                    <td><span class="badge bg-primary">{{ $reporte['info_basica']['carga_maxima'] ?? 'N/A' }} horas</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cargas Académicas --}}
            @if(isset($reporte['cargas']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-book"></i> Cargas Académicas
                            </h5>
                            <span class="badge bg-light text-dark">
                                Total: {{ $reporte['total_horas'] }} horas
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($reporte['cargas']->isEmpty())
                            <p class="text-muted text-center py-3">No tiene cargas académicas asignadas</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Código</th>
                                            <th>Grupo</th>
                                            <th>Horario</th>
                                            <th>Aula</th>
                                            <th>Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte['cargas'] as $carga)
                                            <tr>
                                                <td><strong>{{ $carga->materia->nombre }}</strong></td>
                                                <td>{{ $carga->materia->codigo }}</td>
                                                <td>{{ $carga->grupo->nombre }}</td>
                                                <td>
                                                    <small>
                                                        {{ $carga->horario->dia_semana }}<br>
                                                        {{ substr($carga->horario->hora_inicio, 0, 5) }} -
                                                        {{ substr($carga->horario->hora_fin, 0, 5) }}
                                                    </small>
                                                </td>
                                                <td>{{ $carga->aula->nombre ?? 'Sin aula' }}</td>
                                                <td><span class="badge bg-info">{{ $carga->materia->carga_horaria }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Asistencias --}}
            @if(isset($reporte['asistencias']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-calendar-check"></i> Registro de Asistencias
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Estadísticas --}}
                        <div class="row mb-4">
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3">
                                    <h3 class="mb-0">{{ $reporte['estadisticas_asistencias']['total'] }}</h3>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3 bg-light">
                                    <h3 class="mb-0 text-success">{{ $reporte['estadisticas_asistencias']['presentes'] }}</h3>
                                    <small class="text-muted">Presentes</small>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3">
                                    <h3 class="mb-0 text-warning">{{ $reporte['estadisticas_asistencias']['tardanzas'] }}</h3>
                                    <small class="text-muted">Tardanzas</small>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3">
                                    <h3 class="mb-0 text-danger">{{ $reporte['estadisticas_asistencias']['ausentes'] }}</h3>
                                    <small class="text-muted">Ausentes</small>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3">
                                    <h3 class="mb-0 text-info">{{ $reporte['estadisticas_asistencias']['justificados'] }}</h3>
                                    <small class="text-muted">Justificados</small>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="border rounded p-3 bg-primary text-white">
                                    <h3 class="mb-0">{{ $reporte['estadisticas_asistencias']['porcentaje_asistencia'] }}%</h3>
                                    <small>Asistencia</small>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de Asistencias --}}
                        @if($reporte['asistencias']->isEmpty())
                            <p class="text-muted text-center py-3">No hay registros de asistencia en el período seleccionado</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Materia</th>
                                            <th>Grupo</th>
                                            <th>Horario</th>
                                            <th>Estado</th>
                                            <th>Hora Llegada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte['asistencias'] as $asistencia)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                                <td>{{ $asistencia->materia->nombre }}</td>
                                                <td>{{ $asistencia->grupo->nombre }}</td>
                                                <td><small>{{ substr($asistencia->horario->hora_inicio, 0, 5) }}</small></td>
                                                <td>
                                                    @if($asistencia->estado === 'Presente')
                                                        <span class="badge bg-success">Presente</span>
                                                    @elseif($asistencia->estado === 'Tardanza')
                                                        <span class="badge bg-warning">Tardanza</span>
                                                    @elseif($asistencia->estado === 'Ausente')
                                                        <span class="badge bg-danger">Ausente</span>
                                                    @else
                                                        <span class="badge bg-info">Justificado</span>
                                                    @endif
                                                </td>
                                                <td>{{ $asistencia->hora_llegada ? substr($asistencia->hora_llegada, 0, 5) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Habilitaciones --}}
            @if(isset($reporte['habilitaciones']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-toggle-on"></i> Historial de Habilitaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Estadísticas --}}
                        <div class="row mb-3">
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-2">
                                    <h4>{{ $reporte['estadisticas_habilitaciones']['total'] }}</h4>
                                    <small>Total</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-2">
                                    <h4 class="text-info">{{ $reporte['estadisticas_habilitaciones']['utilizadas'] }}</h4>
                                    <small>Utilizadas</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-2">
                                    <h4 class="text-success">{{ $reporte['estadisticas_habilitaciones']['habilitadas'] }}</h4>
                                    <small>Activas</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-2">
                                    <h4 class="text-danger">{{ $reporte['estadisticas_habilitaciones']['canceladas'] }}</h4>
                                    <small>Canceladas</small>
                                </div>
                            </div>
                        </div>

                        @if($reporte['habilitaciones']->isEmpty())
                            <p class="text-muted text-center py-3">No hay habilitaciones registradas</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Materia</th>
                                            <th>Grupo</th>
                                            <th>Estado</th>
                                            <th>Fecha Utilización</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reporte['habilitaciones'] as $hab)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($hab->fecha)->format('d/m/Y') }}</td>
                                                <td>{{ $hab->cargaAcademica->materia->nombre }}</td>
                                                <td>{{ $hab->cargaAcademica->grupo->nombre }}</td>
                                                <td>
                                                    @if($hab->estado === 'Utilizada')
                                                        <span class="badge bg-info">Utilizada</span>
                                                    @elseif($hab->estado === 'Habilitada')
                                                        <span class="badge bg-success">Habilitada</span>
                                                    @else
                                                        <span class="badge bg-danger">Cancelada</span>
                                                    @endif
                                                </td>
                                                <td>{{ $hab->fecha_utilizacion ? $hab->fecha_utilizacion->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script>
        function descargarPDF() {
            // Obtener los parámetros del formulario
            const form = document.getElementById('formReporte');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            // Redirigir a la ruta de descarga
            window.location.href = '{{ route("admin.reportes.docentes.pdf") }}?' + params;
        }
    </script>
</x-admin-layout>
