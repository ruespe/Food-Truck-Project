<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'María García',    'email' => 'maria@example.com',   'active' => true],
            ['name' => 'Carlos López',    'email' => 'carlos@example.com',  'active' => true],
            ['name' => 'Ana Martínez',    'email' => 'ana@example.com',     'active' => true],
            ['name' => 'Pedro Sánchez',   'email' => 'pedro@example.com',   'active' => true],
            ['name' => 'Laura Fernández', 'email' => 'laura@example.com',   'active' => true],
            ['name' => 'Jordi Puig',      'email' => 'jordi@example.com',   'active' => true],
            ['name' => 'Marta Soler',     'email' => 'marta@example.com',   'active' => true],
            ['name' => 'Pau Rovira',      'email' => 'pau@example.com',     'active' => true],
            ['name' => 'Elena Ruiz',      'email' => 'elena@example.com',   'active' => false],
            ['name' => 'Tomàs Vila',      'email' => 'tomas@example.com',   'active' => false],
        ];

        foreach ($users as $user) {
            User::factory()->create(array_merge([
                'password' => Hash::make('password'),
            ], $user));
        }
    }
}
