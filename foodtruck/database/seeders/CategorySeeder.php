<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bocadillos', 'description' => 'Bocadillos y sándwiches artesanales'],
            ['name' => 'Hamburguesas', 'description' => 'Hamburguesas caseras con ingredientes frescos'],
            ['name' => 'Tapas', 'description' => 'Tapas y raciones para compartir'],
            ['name' => 'Bebidas', 'description' => 'Refrescos, zumos y bebidas frías'],
            ['name' => 'Postres', 'description' => 'Dulces y postres del día'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
