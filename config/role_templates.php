<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plantillas de Roles Predeterminadas
    |--------------------------------------------------------------------------
    |
    | Define las plantillas de roles con sus permisos predeterminados.
    | Cada plantilla incluye un conjunto de permisos que se asignarán
    | automáticamente al crear un rol basado en esa plantilla.
    |
    */

    'templates' => [
        'admin' => [
            'name' => 'Administrador',
            'description' => 'Acceso completo al sistema',
            'permissions' => [
                'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
                'usuarios.asignar_roles', 'usuarios.remover_roles',
                'roles.ver', 'roles.crear', 'roles.editar', 'roles.eliminar',
                'permissions.ver', 'permissions.crear', 'permissions.editar', 'permissions.eliminar',
                'docentes.ver', 'docentes.crear', 'docentes.editar', 'docentes.eliminar',
                'materias.ver', 'materias.crear', 'materias.editar', 'materias.eliminar',
                'grupos.ver', 'grupos.crear', 'grupos.editar', 'grupos.eliminar',
                'carga-academica.ver', 'carga-academica.crear', 'carga-academica.editar', 'carga-academica.eliminar',
                'horarios.ver', 'horarios.crear', 'horarios.editar', 'horarios.eliminar',
                'aulas.ver', 'aulas.crear', 'aulas.editar', 'aulas.eliminar',
                'asistencia.ver', 'asistencia.registrar', 'asistencia.editar', 'asistencia.eliminar',
                'reportes.ver', 'reportes.descargar',
                'bitacora.ver',
                'configuracion.ver', 'configuracion.editar',
            ],
        ],

        'decano' => [
            'name' => 'Decano',
            'description' => 'Gestión académica y visualización de reportes',
            'permissions' => [
                'usuarios.ver',
                'docentes.ver', 'docentes.crear', 'docentes.editar',
                'materias.ver', 'materias.crear', 'materias.editar',
                'grupos.ver', 'grupos.crear', 'grupos.editar',
                'carga-academica.ver', 'carga-academica.crear', 'carga-academica.editar',
                'horarios.ver', 'horarios.crear', 'horarios.editar',
                'aulas.ver',
                'asistencia.ver',
                'reportes.ver', 'reportes.descargar',
                'bitacora.ver',
            ],
        ],

        'docente' => [
            'name' => 'Docente',
            'description' => 'Registro de asistencia y consulta de horarios',
            'permissions' => [
                'horarios.ver',
                'asistencia.ver', 'asistencia.registrar', 'asistencia.editar_propia',
            ],
        ],

        'custom' => [
            'name' => 'Personalizado',
            'description' => 'Rol sin permisos predeterminados (se asignan manualmente)',
            'permissions' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permisos del Sistema
    |--------------------------------------------------------------------------
    |
    | Lista completa de permisos disponibles en el sistema.
    | Se crean automáticamente al ejecutar el seeder.
    |
    */

    'all_permissions' => [
        // Usuarios
        'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
        'usuarios.asignar_roles', 'usuarios.remover_roles',

        // Roles y Permisos
        'roles.ver', 'roles.crear', 'roles.editar', 'roles.eliminar',
        'permissions.ver', 'permissions.crear', 'permissions.editar', 'permissions.eliminar',

        // Docentes
        'docentes.ver', 'docentes.crear', 'docentes.editar', 'docentes.eliminar',

        // Materias
        'materias.ver', 'materias.crear', 'materias.editar', 'materias.eliminar',

        // Grupos
        'grupos.ver', 'grupos.crear', 'grupos.editar', 'grupos.eliminar',

        // Carga Académica
        'carga-academica.ver', 'carga-academica.crear', 'carga-academica.editar', 'carga-academica.eliminar',

        // Horarios
        'horarios.ver', 'horarios.crear', 'horarios.editar', 'horarios.eliminar',

        // Aulas
        'aulas.ver', 'aulas.crear', 'aulas.editar', 'aulas.eliminar',

        // Asistencia
        'asistencia.ver', 'asistencia.registrar', 'asistencia.editar', 'asistencia.editar_propia', 'asistencia.eliminar',

        // Reportes
        'reportes.ver', 'reportes.descargar',

        // Bitácora
        'bitacora.ver',

        // Configuración
        'configuracion.ver', 'configuracion.editar',
    ],
];
