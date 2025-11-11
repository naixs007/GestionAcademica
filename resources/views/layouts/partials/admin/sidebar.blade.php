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
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.ver') || $user->hasPermissionTo('roles.ver') || $user->hasPermissionTo('permissions.ver')))
            <a href="#" class="submenu-toggle {{ Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'active' : '' }}" data-target="security-menu">
                <i class="fa-solid fa-shield-halved"></i> Gestión de Usuarios y Seguridad
                <i class="fa-solid fa-chevron-down ms-auto"></i>
            </a>

            <div id="security-menu" class="submenu {{ Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'show' : '' }}">
                {{-- USUARIOS --}}
                @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.ver'))
                    <a href="{{ route('admin.users.index') }}" class="{{ Route::is('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Usuarios
                    </a>
                @endif

                {{-- ROLES --}}
                @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('roles.ver'))
                    <a href="{{ route('admin.roles.index') }}" class="{{ Route::is('admin.roles.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-tag"></i> Roles
                    </a>
                @endif

                {{-- PERMISOS --}}
                @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('permissions.ver'))
                    <a href="{{ route('admin.permissions.index') }}" class="{{ Route::is('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-key"></i> Permisos
                    </a>
                @endif
            </div>
        @endif

        <hr>

        {{-- 2. Gestión Académica (Materias) --}}
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.ver')))
            {{-- CORREGIDO: Usando 'admin.materias.index' --}}
            <a href="{{ route('admin.materia.index') }}"
               class="{{ Route::is('admin.materia.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book"></i> Gestión Académica
            </a>
            {{-- CORREGIDO: Usando 'admin.materias.create' --}}
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.crear'))
                <a href="{{ route('admin.materia.create') }}" class="ms-3">Crear materias</a>
            @endif
        @endif

        {{-- 3. Gestión de Horarios y Aulas --}}
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('horarios.ver')))
            {{-- CORREGIDO: Usando 'admin.horarios.index' --}}
            <a href="{{ route('admin.horario.index') }}"
               class="{{ Route::is('admin.horario.*') ? 'active' : '' }}">
                <i class="fa-solid fa-hourglass-half"></i> Gestión de Horarios
            </a>
            {{-- CORREGIDO: Usando 'admin.horarios.create' --}}
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('horarios.crear'))
                <a href="{{ route('admin.horario.create') }}" class="ms-3">Crear horarios</a>
            @endif
        @endif
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('aulas.ver')))
            {{-- CORREGIDO: Usando 'admin.aulas.index' --}}
            <a href="{{ route('admin.aula.index') }}"
               class="{{ Route::is('admin.aula.*') ? 'active' : '' }} ms-0">
                <i class="fa-solid fa-building"></i> Aulas
            </a>
        @endif

        <hr>

        {{-- 4. Control de Asistencia Docente --}}
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('asistencia.ver')))
            {{-- CORREGIDO: Usando 'admin.asistencias.index' o si es un recurso singular, debes verificar el nombre --}}
            {{-- Asumo el plural 'asistencias' por convención, pero mantengo tu nombre 'asistencia' si es un recurso singular --}}
            <a href="{{ route('admin.asistencia.index') }}"
               class="{{ Route::is('admin.asistencia.*') ? 'active' : '' }}">
                <i class="fa-solid fa-square-check"></i> Control de Asistencia Docente
            </a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('asistencia.registrar'))
                <a href="{{ route('admin.asistencia.create') }}" class="ms-3">Registrar asistencia</a>
            @endif
            {{--@if($user->hasAnyRole(['admin','super-admin']) || $hasPerm('asistencia.editar_propia'))
                <a href="{{ route('asistencia.propia') }}" class="ms-3">Editar asistencia propia</a>
            @endif--}}
        @endif

        {{-- 5. Reportes --}}
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('reportes.ver')))
            {{-- CORREGIDO: Usando 'admin.reportes.index' --}}
            <a href="{{ route('admin.reporte.index') }}"
               class="{{ Route::is('admin.reporte.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Reportes
            </a>
            {{-- CORREGIDO: Usando 'admin.reportes.download' --}}
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('reportes.descargar'))
                <a href="{{ route('admin.reporte.download') }}" class="ms-3">Descargar reportes</a>
            @endif
        @endif

        {{-- 6. Bitácora --}}
    @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('bitacora.ver')))
            <a href="{{ route('bitacora.index') }}"
               class="{{ Route::is('bitacora.*') ? 'active' : '' }}">
                <i class="fa-solid fa-pen-to-square"></i> Bitácora
            </a>
        @endif
    </div>
</div>
