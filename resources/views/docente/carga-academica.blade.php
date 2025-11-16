@extends('layouts.docente')

@section('title', 'Mi Carga Académica')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fa-solid fa-book"></i> Mi Carga Académica
        </h2>
    </div>

    {{-- Información del Docente --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $docente->user->name }}</h5>
            <p class="mb-0 text-muted">Docente</p>
        </div>
    </div>

    @if($cargas->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa-solid fa-book-open fs-1 d-block mb-2"></i>
                <p class="mb-0">No tienes cargas académicas asignadas.</p>
            </div>
        </div>
    @else
        @foreach($cargas as $dia => $cargasDia)
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-calendar"></i> {{ $dia }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Materia</th>
                                    <th>Código</th>
                                    <th>Grupo</th>
                                    <th>Horario</th>
                                    <th>Aula</th>
                                    <th>Carga Horaria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cargasDia as $carga)
                                    <tr>
                                        <td>{{ $carga->materia->nombre }}</td>
                                        <td>{{ $carga->materia->codigo }}</td>
                                        <td>{{ $carga->grupo->nombre }}</td>
                                        <td>{{ $carga->horario->hora_inicio }} - {{ $carga->horario->hora_fin }}</td>
                                        <td>
                                            @if($carga->aula)
                                                <span class="badge bg-info">{{ $carga->aula->nombre }}</span>
                                            @else
                                                <span class="badge bg-secondary">No asignada</span>
                                            @endif
                                        </td>
                                        <td>{{ $carga->materia->cargaHoraria ?? 'N/A' }} hrs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
