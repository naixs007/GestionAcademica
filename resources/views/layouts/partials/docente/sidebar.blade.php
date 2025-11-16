@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $user = Auth::user();

    // URL del dashboard del docente
    $homeUrl = Route::has('docente.dashboard') ? route('docente.dashboard') : '/';
    $isActiveDashboard = Route::is('docente.dashboard');
@endphp

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4>Gestión Académica</h4>
        <p class="small text-muted mb-0">Panel Docente</p>
    </div>

    <div class="menu">
        {{-- Dashboard --}}
        <a href="{{ $homeUrl }}" class="{{ $isActiveDashboard ? 'active' : '' }}">
            <i class="fa-regular fa-house"></i> Inicio
        </a>

        <hr>

        {{-- Mi Asistencia --}}
        @if ($user && $user->hasRole('docente'))
            <a href="#"
                class="submenu-toggle {{ Route::is('docente.asistencia.*') ? 'active' : '' }}"
                data-target="asistencia-menu">
                <i class="fa-solid fa-clipboard-check"></i> Mi Asistencia
                <i class="fa-solid fa-chevron-down ms-auto"></i>
            </a>

            <div id="asistencia-menu" class="submenu {{ Route::is('docente.asistencia.*') ? 'show' : '' }}">
                {{-- MARCAR ASISTENCIA --}}
                <a href="{{ route('docente.asistencia.marcar') }}"
                    class="{{ Route::is('docente.asistencia.marcar') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-pointer"></i> Marcar Asistencia
                </a>

                {{-- VER MI ASISTENCIA --}}
                <a href="{{ route('docente.asistencia.index') }}"
                    class="{{ Route::is('docente.asistencia.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i> Ver Mi Historial
                </a>
            </div>

            <hr>
        @endif

        {{-- Mi Perfil --}}
        <a href="{{ route('profile.edit') }}" class="{{ Route::is('profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-circle"></i> Mi Perfil
        </a>
    </div>
</div>
