<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de {{ auth()->user()->name  }}</title>

    <!-- CSS compilado con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <!-- Botón toggle móvil-->
    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Navigation-->
    @include('layouts.partials.admin.navigation')

    <!-- Sidebar -->
    @include('layouts.partials.admin.sidebar')

    <!-- Contenido principal-->
    <main class="p-4" style="margin-left: 260px;">

        {{ $slot }}
    </main>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

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
    </script>
</body>
</html>
