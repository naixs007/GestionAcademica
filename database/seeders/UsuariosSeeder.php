<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $decanoRole = Role::firstOrCreate(['name' => 'decano']);
        $docenteRole = Role::firstOrCreate(['name' => 'docente']);

        // Crear usuarios y asignar roles
        $admin = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole($adminRole);

        $decano = User::create([
            'name' => 'Decano Facultad',
            'email' => 'decano@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $decano->assignRole($decanoRole);

        $docente = User::create([
            'name' => 'Docente Ejemplo',
            'email' => 'docente@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $docente->assignRole($docenteRole);
    }
}


