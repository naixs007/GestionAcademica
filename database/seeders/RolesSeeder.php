<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // ROLES
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $decano = Role::firstOrCreate(['name' => 'decano', 'guard_name' => 'web']);
        $docente = Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web']);

        // PERMISOS
        $permissions = [
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
            'usuarios.asignar_roles', 'usuarios.remover_roles',
            'materias.ver', 'materias.crear', 'materias.editar', 'materias.eliminar',
            'horarios.ver', 'horarios.crear', 'horarios.editar',
            'aulas.ver', 'aulas.editar',
            'asistencia.ver', 'asistencia.registrar', 'asistencia.editar_propia',
            'reportes.ver', 'reportes.descargar',
            'bitacora.ver'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ASIGNAR PERMISOS A ROLES
        $admin->syncPermissions($permissions);

        $decano->syncPermissions([
            'usuarios.ver','materias.ver','horarios.ver','asistencia.ver','reportes.ver','bitacora.ver'
        ]);

        $docente->syncPermissions(['horarios.ver','asistencia.registrar','asistencia.ver']);
    }
}

