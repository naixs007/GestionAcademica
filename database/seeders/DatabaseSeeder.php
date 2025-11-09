<?php

namespace Database\Seeders;

use App\Models\User;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

         // Ejecutar primero RolesSeeder para crear roles y permisos
         // antes de crear/actualizar usuarios que dependen de ellos.
         $this->call([
            RolesSeeder::class,
            UsuariosSeeder::class,
        ]);

        //$this->call(UsuariosSeeder::class);
    }
}
