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
        User::firstOrCreate(['email' => 'admin@gmail.com'], [
            'name'              => 'Admin',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'active'            => true,
            'email_verified_at' => now(),
        ]);

        // Cliente de prueba (tendrá varios pedidos asignados)
        User::firstOrCreate(['email' => 'cliente@foodtruck.com'], [
            'name'              => 'Cliente Test',
            'password'          => Hash::make('password'),
            'role'              => 'client',
            'active'            => true,
            'email_verified_at' => now(),
        ]);

        // Usuario de testing: rol admin → accede al panel y salta restricción de horario
        User::firstOrCreate(['email' => 'testing@foodtruck.com'], [
            'name'              => 'Testing',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'active'            => true,
            'email_verified_at' => now(),
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
