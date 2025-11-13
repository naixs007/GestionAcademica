@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;
    $user = Auth::user();

    // Lógica para el Home URL (Usando el dashboard unificado)
    $homeUrl = Route::has('dashboard') ? route('dashboard') : '/';
    // Fallback si la ruta 'dashboard' no existe (mantenemos tu lógica original)
    if (!Route::has('dashboard') && $user) {
        if ($user->hasAnyRole(['admin','super-admin']) && Route::has('admin.dashboard')) $homeUrl = route('admin.dashboard');
        elseif ($user->hasRole('decano') && Route::has('decano.dashboard')) $homeUrl = route('decano.dashboard');
        elseif ($user->hasRole('docente') && Route::has('docente.dashboard')) $homeUrl = route('docente.dashboard');
    }
    // Determinar si la ruta actual es algún dashboard para la clase 'active'
    $isActiveDashboard = Route::is('dashboard') || Route::is('*dashboard');
@endphp

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4>Gestión Académica</h4>
    </div>

    <div class="menu">
        <a href="{{ $homeUrl }}"
            class="{{ $isActiveDashboard ? 'active' : '' }}">
            <i class="fa-regular fa-house"></i> Inicio
        </a>

        {{-- 1. Gestión de Usuarios y Seguridad --}}
        <a href="#" class="submenu-toggle {{ Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'active' : '' }}" data-target="security-menu">
            <i class="fa-solid fa-shield-halved"></i> Gestión de Usuarios y Seguridad
            <i class="fa-solid fa-chevron-down ms-auto"></i>
        </a>

        <div id="security-menu" class="submenu {{ Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'show' : '' }}">
            {{-- USUARIOS --}}
            <a href="{{ route('admin.users.index') }}" class="{{ Route::is('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Usuarios
            </a>

            {{-- ROLES --}}
            <a href="{{ route('admin.roles.index') }}" class="{{ Route::is('admin.roles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tag"></i> Roles
            </a>

            {{-- PERMISOS --}}
            <a href="{{ route('admin.permissions.index') }}" class="{{ Route::is('admin.permissions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-key"></i> Permisos
            </a>
        </div>

        <hr>

        {{-- 2. Gestión Académica (Submenú desplegable) --}}
        <a href="#" class="submenu-toggle {{ Route::is('admin.docentes.*') || Route::is('admin.materia.*') || Route::is('admin.grupos.*') || Route::is('admin.carga-academica.*') || Route::is('admin.configuracion.*') ? 'active' : '' }}" data-target="academic-menu">
            <i class="fa-solid fa-graduation-cap"></i> Gestión Académica
            <i class="fa-solid fa-chevron-down ms-auto"></i>
        </a>

        <div id="academic-menu" class="submenu {{ Route::is('admin.docentes.*') || Route::is('admin.materia.*') || Route::is('admin.grupos.*') || Route::is('admin.carga-academica.*') || Route::is('admin.configuracion.*') ? 'show' : '' }}">
            {{-- DOCENTES --}}
            <a href="{{ route('admin.docentes.index') }}" class="{{ Route::is('admin.docentes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user"></i> Docentes
            </a>

            {{-- MATERIAS --}}
            <a href="{{ route('admin.materia.index') }}" class="{{ Route::is('admin.materia.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book"></i> Materias
            </a>

            {{-- GRUPOS --}}
            <a href="{{ route('admin.grupos.index') }}" class="{{ Route::is('admin.grupos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-rectangle"></i> Grupos
            </a>

            {{-- ASIGNAR CARGA ACADÉMICA --}}
            <a href="{{ route('admin.carga-academica.index') }}" class="{{ Route::is('admin.carga-academica.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check"></i> Asignar Carga Académica
            </a>

            {{-- CONFIGURAR PARÁMETROS GENERALES --}}
            <a href="{{ route('admin.configuracion.index') }}" class="{{ Route::is('admin.configuracion.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Configurar Parámetros
            </a>
        </div>

        <hr>

        {{-- 3. Gestión de Horarios y Aulas --}}
        <a href="#" class="submenu-toggle {{ Route::is('admin.horario.*') || Route::is('admin.aula.*') ? 'active' : '' }}" data-target="horarios-aulas-menu">
            <i class="fa-solid fa-calendar-days"></i> Gestión de Horarios y Aulas
            <i class="fa-solid fa-chevron-down ms-auto"></i>
        </a>

        <div id="horarios-aulas-menu" class="submenu {{ Route::is('admin.horario.*') || Route::is('admin.aula.*') ? 'show' : '' }}">
            {{-- HORARIOS --}}
            <a href="{{ route('admin.horario.index') }}" class="{{ Route::is('admin.horario.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock"></i> Horarios
            </a>

            {{-- AULAS --}}
            <a href="{{ route('admin.aula.index') }}" class="{{ Route::is('admin.aula.*') ? 'active' : '' }}">
                <i class="fa-solid fa-door-open"></i> Aulas
            </a>
        </div>

        <hr>

        {{-- 4. Control de Asistencia Docente --}}
        <a href="{{ route('admin.asistencia.index') }}"
           class="{{ Route::is('admin.asistencia.*') ? 'active' : '' }}">
            <i class="fa-solid fa-square-check"></i> Control de Asistencia Docente
        </a>
        <a href="{{ route('admin.asistencia.create') }}" class="ms-3">Registrar asistencia</a>

        {{-- 5. Reportes --}}
        <a href="{{ route('admin.reporte.index') }}"
           class="{{ Route::is('admin.reporte.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Reportes
        </a>
        <a href="{{ route('admin.reporte.download') }}" class="ms-3">Descargar reportes</a>

        {{-- 6. Bitácora --}}
        <a href="{{ route('bitacora.index') }}"
           class="{{ Route::is('bitacora.*') ? 'active' : '' }}">
            <i class="fa-solid fa-pen-to-square"></i> Bitácora
        </a>
    </div>
</div>
