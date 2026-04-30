<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name'  => 'Admin',
            'email' => 'admin@gmail.com',
        ]);

        // Cliente de prueba
        User::factory()->create([
            'name'  => 'Cliente Test',
            'email' => 'cliente@foodtruck.com',
        ]);

        // Categorías → Productos → Ubicación inicial
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}

