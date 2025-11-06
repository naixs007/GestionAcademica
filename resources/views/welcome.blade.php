<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGA - Sistema de Gestión Académica</title>
    <meta name="description" content="Sistema Web de Gestión Académica para administrar usuarios, horarios, asistencia y reportes académicos">
    <meta name="keywords" content="gestión académica, sistema educativo, administración universitaria, control de asistencia">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>SGA – Sistema de Gestión Académica FICCT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#funcionalidades">Funcionalidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-light">Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container position-relative">
            <div class="row justify-content-center text-center text-white">
                <div class="col-lg-8">
                    <h1 class="display-3 fw-bold mb-4 animate-fade-in">Bienvenido al Sistema de Gestión Académica</h1>
                    <p class="lead mb-5 animate-fade-in-delay">Administra usuarios, horarios, asistencia y reportes desde un solo lugar.</p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 animate-fade-in-delay-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Ir al Login
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="funcionalidades" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary">Funcionalidades Principales</h2>
                <p class="lead text-muted">Herramientas diseñadas para optimizar la gestión educativa</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm card-hover">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-people-fill text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold">Gestión de Usuarios y Roles</h5>
                            <p class="card-text text-muted">Administra perfiles de estudiantes, docentes y personal administrativo con roles personalizados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm card-hover">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-calendar3 text-success"></i>
                            </div>
                            <h5 class="card-title fw-bold">Control de Horarios y Aulas</h5>
                            <p class="card-text text-muted">Organiza horarios académicos, asigna aulas y evita conflictos de programación.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm card-hover">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-check2-circle text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Asistencia Docente</h5>
                            <p class="card-text text-muted">Registra y controla la asistencia de docentes de forma rápida y eficiente.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm card-hover">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-graph-up text-info"></i>
                            </div>
                            <h5 class="card-title fw-bold">Reportes y Estadísticas</h5>
                            <p class="card-text text-muted">Genera reportes detallados y visualiza estadísticas académicas en tiempo real.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80"
                         alt="Campus universitario" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold text-primary mb-4">Nuestra Misión</h2>
                    <p class="lead text-muted mb-4">
                        El SGA permite optimizar la gestión académica mediante herramientas digitales eficientes, seguras y accesibles para toda la comunidad educativa.
                    </p>
                    <p class="text-muted">
                        Nuestra plataforma integra todos los procesos administrativos y académicos en un sistema centralizado, facilitando la toma de decisiones basada en datos y mejorando la experiencia de estudiantes, docentes y personal administrativo.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contacto" class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; 2025 Universidad Ejemplo — Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white text-decoration-none me-3">Política de Privacidad</a>
                    <a href="#" class="text-white text-decoration-none">Términos de Uso</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
