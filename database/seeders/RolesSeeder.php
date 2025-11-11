<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Obtener configuración de plantillas de roles
        $templates = config('role_templates.templates');
        $allPermissions = config('role_templates.all_permissions');

        // Crear todos los permisos del sistema
        foreach ($allPermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Crear roles predeterminados basados en plantillas
        foreach ($templates as $roleKey => $template) {
            // Solo crear roles con nombre específico (admin, decano, docente)
            // 'custom' no se crea automáticamente
            if ($roleKey === 'custom') {
                continue;
            }

            $role = Role::firstOrCreate([
                'name' => $roleKey,
                'guard_name' => 'web'
            ]);

            // Asignar permisos de la plantilla
            if (!empty($template['permissions'])) {
                $role->syncPermissions($template['permissions']);
            }

            $this->command->info("Rol '{$roleKey}' creado/actualizado con " . count($template['permissions']) . " permisos.");
        }

        $this->command->info('Roles y permisos sembrados correctamente desde plantillas.');
    }
}

