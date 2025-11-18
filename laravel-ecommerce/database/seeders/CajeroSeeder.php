<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CajeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe
        if (!User::where('email', 'cajero@nutrishop.com')->exists()) {
            User::create([
                'name' => 'Cajero Demo',
                'email' => 'cajero@nutrishop.com',
                'password' => Hash::make('cajero123'),
                'rol' => 'cajero',
                'email_verified_at' => now(),
            ]);

            $this->command->info('✅ Usuario cajero creado exitosamente!');
            $this->command->info('📧 Email: cajero@nutrishop.com');
            $this->command->info('🔑 Password: cajero123');
        } else {
            $this->command->warn('⚠️  El usuario cajero ya existe.');
        }
    }
}
