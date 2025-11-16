<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Docente - {{ $info_basica['nombre'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
        }
        .header h1 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            background-color: #f0f0f0;
            padding: 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table td {
            padding: 5px 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 4px 8px;
            font-weight: bold;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .info-value {
            display: table-cell;
            padding: 4px 8px;
            border: 1px solid #ddd;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-primary { background-color: #007bff; color: white; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>REPORTE DE DOCENTE</h1>
        <p>Sistema de Gestión Académica</p>
        <p>Generado: {{ $generado_en->format('d/m/Y H:i:s') }}</p>
        @if($fecha_inicio && $fecha_fin)
            <p>Período: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</p>
        @endif
    </div>

    {{-- Información Básica --}}
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL DOCENTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre Completo</div>
                <div class="info-value"><strong>{{ $info_basica['nombre'] }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $info_basica['email'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Especialidad</div>
                <div class="info-value">{{ $info_basica['especialidad'] ?? 'No especificada' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Grado Académico</div>
                <div class="info-value">{{ $info_basica['grado_academico'] ?? 'No especificado' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Teléfono</div>
                <div class="info-value">{{ $info_basica['telefono'] ?? 'No registrado' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Carga Máxima</div>
                <div class="info-value">{{ $info_basica['carga_maxima'] ?? 'N/A' }} horas</div>
            </div>
        </div>
    </div>

    {{-- Cargas Académicas --}}
    @if(isset($cargas) && in_array('cargas', $incluir))
        <div class="section">
            <div class="section-title">CARGAS ACADÉMICAS - Total: {{ $total_horas }} horas</div>
            @if($cargas->isEmpty())
                <p style="text-align: center; padding: 15px; color: #999;">No tiene cargas académicas asignadas</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%;">Materia</th>
                            <th style="width: 12%;">Código</th>
                            <th style="width: 12%;">Grupo</th>
                            <th style="width: 20%;">Horario</th>
                            <th style="width: 16%;">Aula</th>
                            <th style="width: 10%;">Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cargas as $carga)
                            <tr>
                                <td>{{ $carga->materia->nombre }}</td>
                                <td>{{ $carga->materia->codigo }}</td>
                                <td>{{ $carga->grupo->nombre }}</td>
                                <td>
                                    {{ $carga->horario->dia_semana }}<br>
                                    {{ substr($carga->horario->hora_inicio, 0, 5) }} - {{ substr($carga->horario->hora_fin, 0, 5) }}
                                </td>
                                <td>{{ $carga->aula->nombre ?? 'Sin aula' }}</td>
                                <td style="text-align: center;"><strong>{{ $carga->materia->carga_horaria }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    {{-- Asistencias --}}
    @if(isset($asistencias) && in_array('asistencias', $incluir))
        <div class="section page-break">
            <div class="section-title">REGISTRO DE ASISTENCIAS</div>

            {{-- Estadísticas --}}
            <div class="stats-container" style="margin-bottom: 15px;">
                <div class="stat-box">
                    <div class="stat-number">{{ $estadisticas_asistencias['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #28a745;">{{ $estadisticas_asistencias['presentes'] }}</div>
                    <div class="stat-label">Presentes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #ffc107;">{{ $estadisticas_asistencias['tardanzas'] }}</div>
                    <div class="stat-label">Tardanzas</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #dc3545;">{{ $estadisticas_asistencias['ausentes'] }}</div>
                    <div class="stat-label">Ausentes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #17a2b8;">{{ $estadisticas_asistencias['justificados'] }}</div>
                    <div class="stat-label">Justificados</div>
                </div>
                <div class="stat-box" style="background-color: #007bff;">
                    <div class="stat-number" style="color: white;">{{ $estadisticas_asistencias['porcentaje_asistencia'] }}%</div>
                    <div class="stat-label" style="color: white;">% Asistencia</div>
                </div>
            </div>

            @if($asistencias->isEmpty())
                <p style="text-align: center; padding: 15px; color: #999;">No hay registros de asistencia en el período seleccionado</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 12%;">Fecha</th>
                            <th style="width: 30%;">Materia</th>
                            <th style="width: 12%;">Grupo</th>
                            <th style="width: 10%;">Horario</th>
                            <th style="width: 15%;">Estado</th>
                            <th style="width: 11%;">Hora Llegada</th>
                            <th style="width: 10%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $asistencia)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $asistencia->materia->nombre }}</td>
                                <td>{{ $asistencia->grupo->nombre }}</td>
                                <td>{{ substr($asistencia->horario->hora_inicio, 0, 5) }}</td>
                                <td>
                                    @if($asistencia->estado === 'Presente')
                                        <span class="badge badge-success">Presente</span>
                                    @elseif($asistencia->estado === 'Tardanza')
                                        <span class="badge badge-warning">Tardanza</span>
                                    @elseif($asistencia->estado === 'Ausente')
                                        <span class="badge badge-danger">Ausente</span>
                                    @else
                                        <span class="badge badge-info">Justificado</span>
                                    @endif
                                </td>
                                <td>{{ $asistencia->hora_llegada ? substr($asistencia->hora_llegada, 0, 5) : '-' }}</td>
                                <td style="font-size: 8px;">{{ $asistencia->observaciones ? substr($asistencia->observaciones, 0, 30) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    {{-- Habilitaciones --}}
    @if(isset($habilitaciones) && in_array('habilitaciones', $incluir))
        <div class="section">
            <div class="section-title">HISTORIAL DE HABILITACIONES</div>

            {{-- Estadísticas --}}
            <div class="stats-container" style="margin-bottom: 15px;">
                <div class="stat-box">
                    <div class="stat-number">{{ $estadisticas_habilitaciones['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #17a2b8;">{{ $estadisticas_habilitaciones['utilizadas'] }}</div>
                    <div class="stat-label">Utilizadas</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #28a745;">{{ $estadisticas_habilitaciones['habilitadas'] }}</div>
                    <div class="stat-label">Activas</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #dc3545;">{{ $estadisticas_habilitaciones['canceladas'] }}</div>
                    <div class="stat-label">Canceladas</div>
                </div>
            </div>

            @if($habilitaciones->isEmpty())
                <p style="text-align: center; padding: 15px; color: #999;">No hay habilitaciones registradas</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%;">Fecha</th>
                            <th style="width: 35%;">Materia</th>
                            <th style="width: 15%;">Grupo</th>
                            <th style="width: 15%;">Estado</th>
                            <th style="width: 20%;">Fecha Utilización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($habilitaciones as $hab)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($hab->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $hab->cargaAcademica->materia->nombre }}</td>
                                <td>{{ $hab->cargaAcademica->grupo->nombre }}</td>
                                <td>
                                    @if($hab->estado === 'Utilizada')
                                        <span class="badge badge-info">Utilizada</span>
                                    @elseif($hab->estado === 'Habilitada')
                                        <span class="badge badge-success">Habilitada</span>
                                    @else
                                        <span class="badge badge-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>{{ $hab->fecha_utilizacion ? $hab->fecha_utilizacion->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Sistema de Gestión Académica - Reporte generado automáticamente</p>
    </div>
</body>
</html>
