<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Cliente de prueba (tendrá varios pedidos asignados)
        User::factory()->create([
            'name'     => 'Cliente Test',
            'email'    => 'cliente@foodtruck.com',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            LocationSeeder::class,
            OrderSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}
