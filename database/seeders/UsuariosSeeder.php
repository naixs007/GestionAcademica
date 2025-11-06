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
        // Ensure roles exist (in case RolesSeeder wasn't run)
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'decano', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web']);

        // Create or update users by email so the seeder is idempotent
        $admin = User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Administrador',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'estado' => 'activo',
        ]);
        $admin->assignRole('admin');

        $decano = User::updateOrCreate([
            'email' => 'decano@gmail.com',
        ], [
            'name' => 'Decano',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'estado' => 'activo',
        ]);
        $decano->assignRole('decano');

        $docente = User::updateOrCreate([
            'email' => 'docente@gmail.com',
        ], [
            'name' => 'Docente',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'estado' => 'activo',
        ]);
        $docente->assignRole('docente');
    }
}



