@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;
    $user = Auth::user();
@endphp

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4>Gestión Académica</h4>
    </div>

    <div class="menu">
        {{-- Inicio común: elegir dashboard según prefijo de ruta o rol --}}
        @php
            $homeUrl = '/';
            $current = Route::currentRouteName();
            if ($current && Str::startsWith($current, 'admin.')) {
                if (Route::has('admin.dashboard')) $homeUrl = route('admin.dashboard');
            } elseif ($current && Str::startsWith($current, 'decano.')) {
                if (Route::has('decano.dashboard')) $homeUrl = route('decano.dashboard');
            } elseif ($current && Str::startsWith($current, 'docente.')) {
                if (Route::has('docente.dashboard')) $homeUrl = route('docente.dashboard');
            } else {
                if (Route::has('dashboard')) $homeUrl = route('dashboard');
                elseif ($user) {
                    if ($user->hasAnyRole(['admin','super-admin']) && Route::has('admin.dashboard')) $homeUrl = route('admin.dashboard');
                    elseif ($user->hasRole('decano') && Route::has('decano.dashboard')) $homeUrl = route('decano.dashboard');
                    elseif ($user->hasRole('docente') && Route::has('docente.dashboard')) $homeUrl = route('docente.dashboard');
                }
            }
        @endphp

        <a href="{{ $homeUrl }}"><i class="fa-regular fa-house"></i> Inicio</a>

        {{-- 1. Gestión de Usuarios y Accesos --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.ver')))
            <a href="#usuarios"><i class="fa-solid fa-users"></i> Gestión de Usuarios y Accesos</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.crear'))
                <a href="#usuarios/crear" class="ms-3">Crear usuario</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.editar'))
                <a href="#usuarios/editar" class="ms-3">Editar usuario</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.eliminar'))
                <a href="#usuarios/eliminar" class="ms-3">Eliminar usuario</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.asignar_roles'))
                <a href="#usuarios/asignar" class="ms-3">Asignar roles</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('usuarios.remover_roles'))
                <a href="#usuarios/remover" class="ms-3">Remover roles</a>
            @endif
        @endif

        {{-- 2. Gestión Académica --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.ver')))
            <a href="#materias"><i class="fa-solid fa-book"></i> Gestión Académica</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.crear'))
                <a href="#materias/crear" class="ms-3">Crear materias</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.editar'))
                <a href="#materias/editar" class="ms-3">Editar materias</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('materias.eliminar'))
                <a href="#materias/eliminar" class="ms-3">Eliminar materias</a>
            @endif
        @endif

        {{-- 3. Gestión de Horarios y Aulas --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('horarios.ver')))
            <a href="#horarios"><i class="fa-solid fa-hourglass-half"></i> Gestión de Horarios y Aulas</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('horarios.crear'))
                <a href="#horarios/crear" class="ms-3">Crear horarios</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('horarios.editar'))
                <a href="#horarios/editar" class="ms-3">Editar horarios</a>
            @endif
        @endif
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('aulas.ver')))
            <a href="#aulas" class="ms-0"><i class="fa-solid fa-building"></i> Aulas</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('aulas.editar'))
                <a href="#aulas/editar" class="ms-3">Editar aulas</a>
            @endif
        @endif

        {{-- 4. Control de Asistencia Docente --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('asistencia.ver')))
            <a href="#asistencia"><i class="fa-solid fa-square-check"></i> Control de Asistencia Docente</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('asistencia.registrar'))
                <a href="#asistencia/registrar" class="ms-3">Registrar asistencia</a>
            @endif
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('asistencia.editar_propia'))
                <a href="#asistencia/editar" class="ms-3">Editar asistencia propia</a>
            @endif
        @endif

        {{-- 5. Reportes --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('reportes.ver')))
            <a href="#reportes"><i class="fa-solid fa-chart-line"></i> Reportes</a>
            @if($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('reportes.descargar'))
                <a href="#reportes/descargar" class="ms-3">Descargar reportes</a>
            @endif
        @endif

        {{-- 6. Bitácora --}}
        @if($user && ($user->hasAnyRole(['admin','super-admin']) || $user->hasPermissionTo('bitacora.ver')))
            <a href="#bitacora"><i class="fa-solid fa-pen-to-square"></i> Bitácora</a>
        @endif
    </div>
</div>
