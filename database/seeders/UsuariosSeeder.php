<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Crear Permisos (idempotente)
        Permission::firstOrCreate([
            'name' => 'ver usuarios',
            'guard_name' => 'web',
        ]);
        Permission::firstOrCreate([
            'name' => 'crear usuarios',
            'guard_name' => 'web',
        ]);

        // Crear Roles (idempotente)
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $decanoRole = Role::firstOrCreate(['name' => 'decano'], ['guard_name' => 'web']);
        $docenteRole = Role::firstOrCreate(['name' => 'docente'], ['guard_name' => 'web']);

        // Crear/actualizar usuarios y asignar roles (idempotente)
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);
        $permissionsAdmin = Permission::query()->pluck('name');
        $adminRole->syncPermissions($permissionsAdmin);

        $decano = User::query()->updateOrCreate(
            ['email' => 'decano@gmail.com'],
            [
                'name' => 'Decano Facultad',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $decano->assignRole($decanoRole);

        $docente = User::query()->updateOrCreate(
            ['email' => 'docente@gmail.com'],
            [
                'name' => 'Docente Ejemplo',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $docente->assignRole($docenteRole);
    }
}


