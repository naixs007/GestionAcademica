@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $user = Auth::user();

    // URL del dashboard del decano
    $homeUrl = Route::has('decano.dashboard') ? route('decano.dashboard') : '/';
    $isActiveDashboard = Route::is('decano.dashboard');
@endphp

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4>Gestión Académica</h4>
        <p class="small text-muted mb-0">Panel Decano</p>
    </div>

    <div class="menu">
        {{-- Dashboard --}}
        <a href="{{ $homeUrl }}" class="{{ $isActiveDashboard ? 'active' : '' }}">
            <i class="fa-regular fa-house"></i> Inicio
        </a>

        <hr>

        {{-- Gestión Académica --}}
        @if ($user && $user->hasRole('decano'))
            <a href="#"
                class="submenu-toggle {{ Route::is('admin.docentes.*') || Route::is('admin.materia.*') || Route::is('admin.grupos.*') || Route::is('admin.carga-academica.*') ? 'active' : '' }}"
                data-target="academic-menu">
                <i class="fa-solid fa-graduation-cap"></i> Gestión Académica
                <i class="fa-solid fa-chevron-down ms-auto"></i>
            </a>

            <div id="academic-menu"
                class="submenu {{ Route::is('admin.docentes.*') || Route::is('admin.materia.*') || Route::is('admin.grupos.*') || Route::is('admin.carga-academica.*') ? 'show' : '' }}">
                {{-- DOCENTES --}}
                <a href="{{ route('admin.docentes.index') }}"
                    class="{{ Route::is('admin.docentes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i> Docentes
                </a>

                {{-- MATERIAS --}}
                <a href="{{ route('admin.materia.index') }}"
                    class="{{ Route::is('admin.materia.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book"></i> Materias
                </a>

                {{-- GRUPOS --}}
                <a href="{{ route('admin.grupos.index') }}" class="{{ Route::is('admin.grupos.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-rectangle"></i> Grupos
                </a>

                {{-- ASIGNAR CARGA ACADÉMICA --}}
                <a href="{{ route('admin.carga-academica.index') }}"
                    class="{{ Route::is('admin.carga-academica.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Asignar Carga Académica
                </a>
            </div>

            <hr>
        @endif

        {{-- Gestión de Horarios y Aulas --}}
        @if ($user && $user->hasRole('decano'))
            <a href="#"
                class="submenu-toggle {{ Route::is('admin.horario.*') || Route::is('admin.aula.*') ? 'active' : '' }}"
                data-target="horarios-aulas-menu">
                <i class="fa-solid fa-calendar-days"></i> Horarios y Aulas
                <i class="fa-solid fa-chevron-down ms-auto"></i>
            </a>

            <div id="horarios-aulas-menu"
                class="submenu {{ Route::is('admin.horario.*') || Route::is('admin.aula.*') ? 'show' : '' }}">
                {{-- HORARIOS --}}
                <a href="{{ route('admin.horario.index') }}"
                    class="{{ Route::is('admin.horario.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock"></i> Horarios
                </a>

                {{-- AULAS --}}
                <a href="{{ route('admin.aula.index') }}" class="{{ Route::is('admin.aula.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-door-open"></i> Aulas
                </a>
            </div>

            <hr>
        @endif

        {{-- Control de Asistencia Docente --}}
        @if ($user && $user->hasRole('decano'))
            <a href="#"
                class="submenu-toggle {{ Route::is('admin.asistencia.*') || Route::is('admin.habilitaciones.*') ? 'active' : '' }}"
                data-target="asistencia-menu">
                <i class="fa-solid fa-square-check"></i> Control de Asistencia
                <i class="fa-solid fa-chevron-down ms-auto"></i>
            </a>

            <div id="asistencia-menu" class="submenu {{ Route::is('admin.asistencia.*') || Route::is('admin.habilitaciones.*') ? 'show' : '' }}">
                {{-- HABILITAR MARCADO --}}
                <a href="{{ route('admin.habilitaciones.index') }}"
                    class="{{ Route::is('admin.habilitaciones.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-toggle-on"></i> Habilitar Marcado
                </a>

                {{-- VER ASISTENCIAS --}}
                <a href="{{ route('admin.asistencia.index') }}"
                    class="{{ Route::is('admin.asistencia.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i> Ver Asistencias
                </a>

                {{-- REGISTRAR ASISTENCIA --}}
                <a href="{{ route('admin.asistencia.create') }}"
                    class="{{ Route::is('admin.asistencia.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-plus-circle"></i> Registrar Asistencia
                </a>
            </div>

            <hr>
        @endif

        {{-- Reportes --}}
        @if ($user && $user->hasRole('decano'))
            <a href="{{ route('admin.reportes.docentes.index') }}"
                class="{{ Route::is('admin.reportes.docentes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Reportes de Docentes
            </a>

            <hr>
        @endif

        {{-- Mi Perfil --}}
        <a href="{{ route('profile.edit') }}" class="{{ Route::is('profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-circle"></i> Mi Perfil
        </a>
    </div>
</div>
