<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // Proteínas
            [
                'nombre' => 'Whey Protein',
                'descripcion' => 'Proteina de suero de alta calidad',
                'precio' => 2700.00,
                'stock' => 50,
                'imagen' => 'ProteinaWhey.png',
                'slug' => 'whey-protein',
                'categoria_id' => 1, // Proteínas
                'activo' => true,
                'destacado' => true,
            ],
            [
                'nombre' => 'Iso 100',
                'descripcion' => 'Proteina aislada de rapida absorcion',
                'precio' => 3200.00,
                'precio_oferta' => 2800.00,
                'stock' => 30,
                'imagen' => 'iso100.png',
                'slug' => 'iso-100',
                'categoria_id' => 1,
                'activo' => true,
                'destacado' => true,
            ],
            [
                'nombre' => 'Mass Gainer',
                'descripcion' => 'Ganador de peso con proteinas y carbohidratos',
                'precio' => 1980.00,
                'stock' => 25,
                'imagen' => 'mass_gainer.png',
                'slug' => 'mass-gainer',
                'categoria_id' => 1,
                'activo' => true,
                'destacado' => false,
            ],

            // Creatinas
            [
                'nombre' => 'Creatina Evolution',
                'descripcion' => 'Creatina monohidrato pura',
                'precio' => 890.00,
                'stock' => 100,
                'imagen' => 'creatina_evolution.png',
                'slug' => 'creatina-evolution',
                'categoria_id' => 2, // Creatinas
                'activo' => true,
                'destacado' => false,
            ],
            [
                'nombre' => 'Creatina Basic',
                'descripcion' => 'Creatina micronizada para mejor absorcion',
                'precio' => 750.00,
                'precio_oferta' => 650.00,
                'stock' => 80,
                'imagen' => 'creatine_basic.png',
                'slug' => 'creatina-basic',
                'categoria_id' => 2,
                'activo' => true,
                'destacado' => true,
            ],
            [
                'nombre' => 'Creatina Epiq',
                'descripcion' => 'Formula avanzada de creatina',
                'precio' => 1100.00,
                'stock' => 35,
                'imagen' => 'creatina_epiq.png',
                'slug' => 'creatina-epiq',
                'categoria_id' => 2,
                'activo' => true,
                'destacado' => false,
            ],

            // Pre-Entreno
            [
                'nombre' => 'Pre-Entreno C4',
                'descripcion' => 'Energia explosiva para tus entrenamientos',
                'precio' => 1450.00,
                'stock' => 60,
                'imagen' => 'Pre-Entreno_C4.png',
                'slug' => 'pre-entreno-c4',
                'categoria_id' => 3, // Pre-Entreno
                'activo' => true,
                'destacado' => false,
            ],
            [
                'nombre' => 'Pre-War',
                'descripcion' => 'Pre-entreno de alta potencia',
                'precio' => 1650.00,
                'precio_oferta' => 1400.00,
                'stock' => 45,
                'imagen' => 'Pre-Entreno_PreWar.png',
                'slug' => 'pre-war',
                'categoria_id' => 3,
                'activo' => true,
                'destacado' => true,
            ],
            [
                'nombre' => 'Pre-Entreno Gold Standard',
                'descripcion' => 'Pre-entreno de calidad premium',
                'precio' => 1850.00,
                'stock' => 30,
                'imagen' => 'Pre-Entreno_GoldStandard.png',
                'slug' => 'pre-entreno-gold-standard',
                'categoria_id' => 3,
                'activo' => true,
                'destacado' => false,
            ],

            // Vitaminas
            [
                'nombre' => 'Omega-3',
                'descripcion' => 'Acidos grasos esenciales para salud cardiovascular',
                'precio' => 580.00,
                'stock' => 80,
                'imagen' => 'omega-3.png',
                'slug' => 'omega-3',
                'categoria_id' => 4, // Vitaminas
                'activo' => true,
                'destacado' => false,
            ],
            [
                'nombre' => 'Vitamina D3',
                'descripcion' => 'Suplemento de vitamina D para huesos fuertes',
                'precio' => 420.00,
                'precio_oferta' => 350.00,
                'stock' => 70,
                'imagen' => 'vitaminaD3.png',
                'slug' => 'vitamina-d3',
                'categoria_id' => 4,
                'activo' => true,
                'destacado' => true,
            ],
            [
                'nombre' => 'Vitamina C',
                'descripcion' => 'Fortalece el sistema inmunologico',
                'precio' => 350.00,
                'stock' => 55,
                'imagen' => 'vitaminaC.png',
                'slug' => 'vitamina-c',
                'categoria_id' => 4,
                'activo' => true,
                'destacado' => false,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
