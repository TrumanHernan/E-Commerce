<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Optimum Nutrition',
                'empresa' => 'Glanbia Performance Nutrition',
                'email' => 'ventas@optimumnutrition.com',
                'telefono' => '+1-800-705-5226',
                'direccion' => '600 S. Cherry Street, Suite 200',
                'ciudad' => 'Denver, CO',
                'pais' => 'Estados Unidos',
                'notas' => 'Proveedor principal de proteínas y suplementos deportivos',
                'activo' => true,
            ],
            [
                'nombre' => 'MuscleTech',
                'empresa' => 'Iovate Health Sciences',
                'email' => 'contacto@muscletech.com',
                'telefono' => '+1-888-334-4448',
                'direccion' => '3880 Jeffrey Blvd',
                'ciudad' => 'Blasdell, NY',
                'pais' => 'Estados Unidos',
                'notas' => 'Especialista en creatinas y pre-entrenos',
                'activo' => true,
            ],
            [
                'nombre' => 'Cellucor',
                'empresa' => 'Nutrabolt',
                'email' => 'info@cellucor.com',
                'telefono' => '+1-866-927-9686',
                'direccion' => '3780 Kilroy Airport Way',
                'ciudad' => 'Long Beach, CA',
                'pais' => 'Estados Unidos',
                'notas' => 'Conocido por la línea C4 de pre-entrenos',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
