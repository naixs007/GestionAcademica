<x-admin-layout>
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h3 mb-2">Panel Administrativo</h2>
                <p class="text-muted">Bienvenido al sistema de gestión académica</p>
            </div>
        </div>

        <!-- Cards de estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-people-fill text-primary fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Usuarios</h6>
                                <h3 class="mb-0">150</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-book-fill text-success fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Materias</h6>
                                <h3 class="mb-0">45</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-badge-fill text-warning fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Docentes</h6>
                                <h3 class="mb-0">28</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check-fill text-info fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Asistencias</h6>
                                <h3 class="mb-0">98%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-people fs-2 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Gestionar Usuarios</h6>
                                            <small class="text-muted">Administrar usuarios del sistema</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.materia.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-book fs-2 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Gestionar Materias</h6>
                                            <small class="text-muted">Administrar materias académicas</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.docentes.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-person-badge fs-2 text-warning me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Gestionar Docentes</h6>
                                            <small class="text-muted">Administrar docentes</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.carga-academica.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-calendar-week fs-2 text-info me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Carga Académica</h6>
                                            <small class="text-muted">Asignar materias a docentes</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.asistencia.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-check-circle fs-2 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Control de Asistencia</h6>
                                            <small class="text-muted">Ver y registrar asistencias</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('admin.bitacora.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                        <i class="bi bi-journal-text fs-2 text-secondary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Bitácora</h6>
                                            <small class="text-muted">Registro de actividades</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
    </style>
</x-admin-layout>

