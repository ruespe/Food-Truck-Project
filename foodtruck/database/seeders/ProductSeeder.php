<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bocadillos  = Category::where('name', 'Bocadillos')->first();
        $hamburguesas = Category::where('name', 'Hamburguesas')->first();
        $tapas       = Category::where('name', 'Tapas')->first();
        $bebidas     = Category::where('name', 'Bebidas')->first();
        $postres     = Category::where('name', 'Postres')->first();

        $products = [
            // Hot dogs
            ['category_id' => $bocadillos->id, 'name' => 'Bocata de calamares', 'description' => 'Calamares a la romana con alioli casero en pan artesano.', 'price' => 5.50, 'stock' => 20],
            ['category_id' => $bocadillos->id, 'name' => 'Bocata de lomo', 'description' => 'Lomo de cerdo a la plancha con pimientos y queso fundido.', 'price' => 5.00, 'stock' => 20],
            // Hamburguesas
            ['category_id' => $hamburguesas->id, 'name' => 'Hamburguesa', 'description' => 'Ternera 100%, lechuga, tomate, cebolla y salsa especial.', 'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689259/burger_is4gca.webp','price' => 7.00, 'stock' => 15],
            // Tapas
            ['category_id' => $tapas->id, 'name' => 'Patatas bravas', 'description' => 'Patatas crujientes con salsa brava y alioli.', 'price' => 3.50, 'stock' => 30],
            ['category_id' => $tapas->id, 'name' => 'Croquetas (6 uds)', 'description' => 'Croquetas caseras de jamón ibérico.', 'price' => 4.00, 'stock' => 25],
            // Bebidas
            ['category_id' => $bebidas->id, 'name' => 'Refresco lata', 'description' => 'Coca-Cola, Fanta o Nestea.', 'price' => 1.50, 'stock' => 50],
            ['category_id' => $bebidas->id, 'name' => 'Agua mineral', 'description' => 'Botella 50 cl.', 'price' => 1.00, 'stock' => 50],
            // Postres
            ['category_id' => $postres->id, 'name' => 'Brownie de chocolate', 'description' => 'Brownie casero con nueces y helado de vainilla.', 'price' => 3.50, 'stock' => 15],
            ['category_id' => $postres->id, 'name' => 'Fruta del tiempo', 'description' => 'Selección de frutas frescas de temporada.',  'price' => 2.50, 'stock' => 20],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['available' => true]));
        }
    }
}
