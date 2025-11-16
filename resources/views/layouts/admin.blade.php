<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de {{ auth()->user()->name  }}</title>

    <!-- CSS compilado con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <!-- Overlay oscuro para móviles -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Botón toggle móvil-->
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <i class="bi bi-list"></i>
    </button>

    <!-- Navigation-->
    @include('layouts.partials.admin.navigation')

    <!-- Sidebar -->
    @include('layouts.partials.admin.sidebar')

    <!-- Contenido principal-->
    <main>
      {{ $slot }}
    </main>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Toggle sidebar en móvil
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        // Cerrar sidebar al hacer click en overlay
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Cerrar sidebar al hacer click en un enlace (solo en móvil)
        if (window.innerWidth <= 992) {
            const sidebarLinks = sidebar.querySelectorAll('.menu a:not(.submenu-toggle)');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            });
        }

        // Manejo de submenús desplegables
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggles = document.querySelectorAll('.submenu-toggle[data-target]');

            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);

                    if (submenu) {
                        // Toggle clase show
                        submenu.classList.toggle('show');
                        this.classList.toggle('active');
                    }
                });
            });
        });

        // Ajustar al cambiar tamaño de ventana
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
