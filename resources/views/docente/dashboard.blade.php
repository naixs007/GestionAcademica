<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de {{auth()->user()->name}}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>

    @include('layouts.partials.admin.navigation')

    @include('layouts.partials.admin.sidebar')

    <main class="p-4" style="margin-left: 260px;">
        <div class="container-fluid">
            <h2>Panel de {{auth()->user()->name}}</h2>
            <p>Bienvenido, {{auth()->user()->name}}. Utilizando temporalmente la estructura de navegación y sidebar del Administrador.</p>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        **Recuerda:** Los enlaces de navegación pueden ser incorrectos si la barra del administrador tiene rutas a las que el decano no debe acceder.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        // Asegúrate de que tu sidebar incluido tenga el ID 'sidebar'
        const sidebar = document.getElementById('sidebar');

        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
</body>
</html>
