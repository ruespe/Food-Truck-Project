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

        $products = [
            // Hot dog
            ['category_id' => $bocadillos->id, 'name' => 'Hot Dog', 'description' => ['es' => 'Salchicha de Frankfurt en pan brioche con ketchup y mostaza.', 'ca' => 'Salsitxa de Frankfurt en pa briox amb kètxup i mostassa.', 'en' => 'Frankfurt sausage in brioche bun with ketchup and mustard.'], 'price' => 2.50],
            // Hamburguesas
            ['category_id' => $hamburguesas->id, 'name' => 'Hamburguesa', 'description' => ['es' => 'Ternera 100%, lechuga, tomate, cebolla y salsa especial.', 'ca' => 'Vedella 100%, enciam, tomàquet, ceba i salsa especial.', 'en' => '100% beef, lettuce, tomato, onion and special sauce.'], 'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689259/burger_is4gca.webp', 'price' => 7.00],
            // Tapas
            ['category_id' => $tapas->id, 'name' => 'Alitas de pollo barbacoa', 'description' => ['es' => 'Alitas de pollo marinadas en salsa barbacoa y asadas al momento.', 'ca' => 'Aletes de pollastre marinades en salsa barbacoa i rostides al moment.', 'en' => 'Chicken wings marinated in barbecue sauce and grilled fresh.'], 'price' => 2.90],
            ['category_id' => $tapas->id, 'name' => 'Nuggets de pollo', 'description' => ['es' => 'Nuggets de pollo crujientes, dorados y jugosos por dentro.', 'ca' => 'Nuggets de pollastre cruixents, daurats i suculents per dins.', 'en' => 'Crispy chicken nuggets, golden and juicy inside.'], 'price' => 2.50],
            ['category_id' => $tapas->id, 'name' => 'Alitas de pollo crujiente', 'description' => ['es' => 'Alitas de pollo rebozadas con un crujiente dorado irresistible.', 'ca' => 'Aletes de pollastre arrebossades amb un cruixent daurat irresistible.', 'en' => 'Breaded chicken wings with an irresistible golden crunch.'], 'price' => 2.90],
            ['category_id' => $tapas->id, 'name' => 'Patatas fritas', 'description' => ['es' => 'Patatas fritas caseras crujientes con sal.', 'ca' => 'Patates fregides casolanes cruixents amb sal.', 'en' => 'Crispy homemade french fries with salt.'], 'price' => 1.80],
            // Bebidas
            ['category_id' => $bebidas->id, 'name' => 'Fuze Tea', 'description' => ['es' => 'Té helado con sabor a fruta, refrescante y suave.', 'ca' => 'Te gelat amb sabor a fruita, refrescant i suau.', 'en' => 'Iced fruit-flavoured tea, refreshing and smooth.'], 'price' => 13.95],
            ['category_id' => $bebidas->id, 'name' => 'Coca-Cola', 'description' => ['es' => 'Refresco de cola clásico bien frío.', 'ca' => 'Refresc de cola clàssic ben fred.', 'en' => 'Classic cold cola drink.'], 'price' => 14.85],
            ['category_id' => $bebidas->id, 'name' => 'Red Bull', 'description' => ['es' => 'Bebida energética, te da alas.', 'ca' => 'Beguda energètica, et dóna ales.', 'en' => 'Energy drink, gives you wings.'], 'price' => 21.35],
            ['category_id' => $bebidas->id, 'name' => 'Fanta Taronja', 'description' => ['es' => 'Refresco de naranja con su sabor afrutado característico.', 'ca' => 'Refresc de taronja amb el seu sabor afruitat característic.', 'en' => 'Orange soft drink with its characteristic fruity flavour.'], 'price' => 11.99],
            ['category_id' => $bebidas->id, 'name' => 'Fanta Llimona', 'description' => ['es' => 'Refresco de limón refrescante y cítrico.', 'ca' => 'Refresc de llimona refrescant i cítric.', 'en' => 'Refreshing and citrusy lemon soft drink.'], 'price' => 11.99],
            ['category_id' => $bebidas->id, 'name' => 'Aquarius Taronja', 'description' => ['es' => 'Bebida isotónica sabor naranja, repone energías.', 'ca' => 'Beguda isotònica sabor taronja, reposa energies.', 'en' => 'Orange-flavoured isotonic drink, restores energy.'], 'price' => 16.56],
            ['category_id' => $bebidas->id, 'name' => 'Aquarius Llimona', 'description' => ['es' => 'Bebida isotónica sabor limón, repone energías.', 'ca' => 'Beguda isotònica sabor llimona, reposa energies.', 'en' => 'Lemon-flavoured isotonic drink, restores energy.'], 'price' => 16.56],
            ['category_id' => $bebidas->id, 'name' => 'Aigua petita', 'description' => ['es' => 'Botella de agua mineral pequeña bien fría.', 'ca' => "Ampolla d'aigua mineral petita ben freda.", 'en' => 'Small cold mineral water bottle.'], 'price' => 10.25],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['available' => true]));
        }
    }
}
