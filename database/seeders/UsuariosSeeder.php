<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

        // ---------------------------------------------------------------
        // Crear más usuarios docentes para el seeder
        $docentes = [
            ['name' => 'María García', 'email' => 'maria.garcia@gmail.com', 'password' => '12345', 'cargaHoraria' => 20, 'categoria' => 'Titular', 'profesion' => 'Lic.'],
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@gmail.com',
                'password' => '12345',
                'cargaHoraria' => 15,
                'categoria' => 'Titular',
                'profesion' => 'Lic.'
            ],
            [
                'name' => 'Carmen Martínez',
                'email' => 'carmen.martinez@gmail.com',
                'password' => '12345',
                'cargaHoraria' => 15,
                'categoria' => 'Titular',
                'profesion' => 'Lic.'
            ],
            [
                'name' => 'Laura Rodríguez',
                'email' => 'laura.rodriguez@gmail.com',
                'password' => '12345',
                'cargaHoraria' => 20,
                'categoria' => 'Titular',
                'profesion' => 'Lic.'
            ],
            [
                'name' => 'Sofía Hernández',
                'email' => 'sofia.hernandez@gmail.com',
                'password' => '12345',
                'cargaHoraria' => 20,
                'categoria' => 'Titular',
                'profesion' => 'Lic.'
            ],
            [
                'name' => 'Isabella Torres',
                'email' => 'isabella.torres@gmail.com',
                'password' => '12345',
                'cargaHoraria' => 15,
                'categoria' => 'Titular',
                'profesion' => 'Lic.'
            ],
        ];

        foreach ($docentes as $docenteData) {
            // Use updateOrCreate to make seeder idempotent and Hash the password
            $user = User::updateOrCreate(
                ['email' => $docenteData['email']],
                [
                    'name' => $docenteData['name'],
                    'password' => Hash::make($docenteData['password']),
                    'email_verified_at' => now(),
                    'estado' => 'activo',
                ]
            );

            // Assign role by name (roles were created above)
            $user->assignRole('docente');
        }

        // Crear más usuarios decanos para el seeder
        $decanos = [
            ['name' => 'Valentina Ruiz', 'email' => 'valentina.ruiz@magy.com', 'password' => '12345'],
            ['name' => 'Camila Díaz', 'email' => 'camila.diaz@magy.com', 'password' => '12345'],
            ['name' => 'Gabriela Morales', 'email' => 'gabriela.morales@magy.com', 'password' => '12345'],
            ['name' => 'Daniela Castro', 'email' => 'daniela.castro@magy.com', 'password' => '12345'],
            ['name' => 'Natalia Ramos', 'email' => 'natalia.ramos@magy.com', 'password' => '12345'],
            ['name' => 'Fernanda Silva', 'email' => 'fernanda.silva@magy.com', 'password' => '12345'],
        ];

        foreach ($decanos as $decanoData) {
            $user = User::updateOrCreate(
                ['email' => $decanoData['email']],
                [
                    'name' => $decanoData['name'],
                    'password' => Hash::make($decanoData['password']),
                    'email_verified_at' => now(),
                    'estado' => 'activo',
                ]
            );

            $user->assignRole('decano');
        }
    }
}
