<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Proteínas',
                'descripcion' => 'Suplementos de proteína para desarrollo y recuperación muscular',
                'slug' => 'proteinas',
            ],
            [
                'nombre' => 'Creatinas',
                'descripcion' => 'Creatina para aumentar la fuerza y el rendimiento',
                'slug' => 'creatinas',
            ],
            [
                'nombre' => 'Pre-Entreno',
                'descripcion' => 'Suplementos pre-entreno para máxima energía y rendimiento',
                'slug' => 'pre-entreno',
            ],
            [
                'nombre' => 'Vitaminas',
                'descripcion' => 'Vitaminas y minerales para tu salud general',
                'slug' => 'vitaminas',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
