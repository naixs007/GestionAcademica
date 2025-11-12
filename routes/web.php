<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\CargaAcademicaController;
use App\Http\Controllers\ConfiguracionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

//--- Rutas de Usuario Autenticado (Generales) ---

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');
    // Ruta para la Bitácora
    Route::get('bitacora', [UserController::class, 'bitacora'])->name('bitacora.index');

    // Rutas de Perfil (Generales para cualquier usuario)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//--- Rutas de Dashboards por Rol ---

// Panel administrador
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// Redirección de conveniencia
Route::redirect('/admin', '/admin/dashboard')->middleware(['auth', 'verified']);

// Panel decano
Route::get('/decano/dashboard', function () {
    return view('decano.dashboard');
})->middleware(['auth', 'verified'])->name('decano.dashboard');

// Panel docente
Route::get('/docente/dashboard', function () {
    return view('docente.dashboard');
})->middleware(['auth', 'verified'])->name('docente.dashboard');

//--- Rutas de Administración (Consolidado) ---

// Todas las rutas bajo el prefijo 'admin' requieren autenticación y verificación
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {

    // 1. Recurso User (Mantenemos este si ya funciona con UserController)
    Route::resource('users', UserController::class)->names('users');

    // Rutas específicas para roles y permisos de user (Ajustadas a singular 'user')
    Route::get('user/{user}/roles-permission', [UserController::class, 'editRoles'])
    ->name('user.roles.edit');
    Route::put('user/{user}/roles-permission', [UserController::class, 'updateRoles'])
    ->name('user.roles.update');
    Route::get('user/{user}/roles/data', [UserController::class, 'getRolesData'])
    ->name('user.roles.data');


    // 2. RECURSOS ACADÉMICOS (VERSIONES TEMPORALES CON FUNCIONES ANÓNIMAS)

    // Docente (Resource completo)
    Route::resource('docentes', DocenteController::class)->names('docentes');

    // Materia (Resource completo)
    Route::resource('materia', MateriaController::class)->names('materia');

    // Grupo (Resource completo)
    Route::resource('grupos', GrupoController::class)->names('grupos');

    // Carga Académica (Gestión de asignación de materias a docentes)
    Route::resource('carga-academica', CargaAcademicaController::class)
        ->parameters(['carga-academica' => 'docente'])
        ->names('carga-academica');

    // Configuración de Parámetros Generales
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::get('configuracion/editar', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    Route::post('configuracion/reset', [ConfiguracionController::class, 'reset'])->name('configuracion.reset');

    // Horario (Temporal)
    // Route::resource('horario', HorarioController::class)->names('horario'); // RUTA ORIGINAL
    Route::get('horario', function() {
        return "Ruta: admin.horario.index (Listado de Horarios) - OK";
    })->name('horario.index');
    Route::get('horario/create', function() {
        return "Ruta: admin.horario.create (Formulario de Horario) - OK";
    })->name('horario.create');

    // Aula (Temporal)
    // Route::resource('aula', AulaController::class)->names('aula'); // RUTA ORIGINAL
    Route::get('aula', function() {
        return "Ruta: admin.aula.index (Listado de Aulas) - OK";
    })->name('aula.index');

    // 3. REPORTE (Temporal)
    // Route::get('reporte', [ReporteController::class, 'index'])->name('reporte.index'); // RUTA ORIGINAL
    Route::get('reporte', function() {
        return "Ruta: admin.reporte.index (Listado de Reportes) - OK";
    })->name('reporte.index');

    // Route::get('reporte/download', [ReporteController::class, 'download'])->name('reporte.download'); // RUTA ORIGINAL
    Route::get('reporte/download', function() {
        return "Ruta: admin.reporte.download (Descarga de Reporte) - OK";
    })->name('reporte.download');

    // 4. ASISTENCIA
    // Usar la ruta resource que apunta al AsistenciaController (crea index/create/store/show/edit/update/destroy)
    Route::resource('asistencia', AsistenciaController::class)->names('asistencia');

    // Roles y Permisos (CRUD básico)
    Route::resource('roles', RoleController::class)->names('roles');
    Route::get('roles/template/{template}/permissions', [RoleController::class, 'getTemplatePermissions'])
        ->name('roles.template.permissions');
    Route::resource('permissions', PermissionController::class)->names('permissions');

    // Panel unificado de Seguridad (Usuarios, Roles, Permisos)
    Route::get('security', [SecurityController::class, 'index'])->name('security.index');

    // Ruta específica de asistencia propia (si necesitas una acción personalizada la puedes implementar
    // en AsistenciaController como 'editPropia' y registrar la ruta apuntando al método)
    Route::get('asistencia/propia', function() {
        return "Ruta: admin.asistencia.propia (Asistencia Propia) - OK";
    })->name('asistencia.propia');
});


//--- Ruta /dashboard Unificada (Lógica de Redirección por Rol) ---

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user) return redirect('/');

    // Redirección basada en rol
    if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['admin', 'super-admin']) && Route::has('admin.dashboard')) {
        return redirect()->route('admin.dashboard');
    }
    if (method_exists($user, 'hasRole') && $user->hasRole('decano') && Route::has('decano.dashboard')) {
        return redirect()->route('decano.dashboard');
    }
    if (method_exists($user, 'hasRole') && $user->hasRole('docente') && Route::has('docente.dashboard')) {
        return redirect()->route('docente.dashboard');
    }

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

//--- Archivos de Rutas Adicionales ---

require __DIR__ . '/auth.php';
