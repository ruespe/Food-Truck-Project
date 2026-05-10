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
            [
                'name' => ['es' => 'Bocadillos', 'ca' => 'Entrepans', 'en' => 'Sandwiches'],
                'description' => [
                    'es' => 'Bocadillos y sándwiches artesanales',
                    'ca' => 'Entrepans i sandvitxos artesanals',
                    'en' => 'Artisan sandwiches and rolls',
                ],
            ],
            [
                'name' => ['es' => 'Hamburguesas', 'ca' => 'Hamburgueses', 'en' => 'Burgers'],
                'description' => [
                    'es' => 'Hamburguesas caseras con ingredientes frescos',
                    'ca' => 'Hamburgueses caseres amb ingredients frescos',
                    'en' => 'Homemade burgers with fresh ingredients',
                ],
            ],
            [
                'name' => ['es' => 'Tapas', 'ca' => 'Tapes', 'en' => 'Tapas'],
                'description' => [
                    'es' => 'Tapas y raciones para compartir',
                    'ca' => 'Tapes i racions per compartir',
                    'en' => 'Tapas and sharing plates',
                ],
            ],
            [
                'name' => ['es' => 'Bebidas', 'ca' => 'Begudes', 'en' => 'Drinks'],
                'description' => [
                    'es' => 'Refrescos, zumos y bebidas frías',
                    'ca' => 'Refrescos, sucs i begudes fredes',
                    'en' => 'Soft drinks, juices and cold beverages',
                ],
            ],
            [
                'name' => ['es' => 'Postres', 'ca' => 'Postres', 'en' => 'Desserts'],
                'description' => [
                    'es' => 'Dulces y postres del día',
                    'ca' => 'Dolcos i postres del dia',
                    'en' => "Today's sweets and desserts",
                ],
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
