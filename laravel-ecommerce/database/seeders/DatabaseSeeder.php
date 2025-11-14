<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        User::create([
            'name' => 'Admin',
            'email' => 'admin@nutrishop.com',
            'password' => bcrypt('admin123'),
            'rol' => 'admin',
        ]);

        User::create([
            'name' => 'Usuario Demo',
            'email' => 'usuario@nutrishop.com',
            'password' => bcrypt('usuario123'),
            'rol' => 'user',
        ]);

        // Ejecutar seeders de categorías y productos
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
