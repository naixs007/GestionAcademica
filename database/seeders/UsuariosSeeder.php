<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Administrador
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin', // rol
        ]);

        // Decano
        User::create([
            'name' => 'Decano Facultad',
            'email' => 'decano@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'decano',
        ]);

        // Docente
        User::create([
            'name' => 'Docente Ejemplo',
            'email' => 'docente@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'docente',
        ]);
    }
}

