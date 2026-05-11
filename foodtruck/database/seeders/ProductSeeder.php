<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::all()->keyBy(fn($c) => $c->name['es'] ?? $c->name);
        $bocadillos   = $cats['Bocadillos'];
        $hamburguesas = $cats['Hamburguesas'];
        $tapas        = $cats['Tapas'];
        $bebidas      = $cats['Bebidas'];

        $products = [
// Hot dog
            ['category_id' => $bocadillos->id,   'name' => ['es' => 'Hot Dog',                    'ca' => 'Hot Dog',                      'en' => 'Hot Dog'],                 'description' => ['es' => 'Salchicha de Frankfurt en pan brioche con ketchup y mostaza.',  'ca' => 'Salsitxa de Frankfurt en pa briox amb kètxup i mostassa.',  'en' => 'Frankfurt sausage in brioche bun with ketchup and mustard.'],  'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689250/hot_dog_ljcfvg.webp', 'price' => 4.00],
            
            // Hamburguesas
            ['category_id' => $hamburguesas->id, 'name' => ['es' => 'Hamburguesa',                'ca' => 'Hamburguesa',                  'en' => 'Burger'],                  'description' => ['es' => 'Ternera 100%, lechuga, tomate, cebolla y salsa especial.',       'ca' => 'Vedella 100%, enciam, tomàquet, ceba i salsa especial.',     'en' => '100% beef, lettuce, tomato, onion and special sauce.'],       'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689259/burger_is4gca.webp', 'price' => 7.50],
            
            // Tapas
            ['category_id' => $tapas->id,        'name' => ['es' => 'Alitas de pollo barbacoa',  'ca' => 'Aletes de pollastre barbacoa', 'en' => 'BBQ Chicken Wings'],        'description' => ['es' => 'Alitas de pollo marinadas en salsa barbacoa y asadas al momento.', 'ca' => 'Aletes de pollastre marinades en salsa barbacoa i rostides al moment.', 'en' => 'Chicken wings marinated in barbecue sauce and grilled fresh.'],  'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1778446711/pngtree-sticky-bbq-chicken-wings-with-sesame-and-dip-image_19540388_eayjk1.webp', 'price' => 5.00],
            ['category_id' => $tapas->id,        'name' => ['es' => 'Nuggets de pollo',          'ca' => 'Nuggets de pollastre',         'en' => 'Chicken Nuggets'],          'description' => ['es' => 'Nuggets de pollo crujientes, dorados y jugosos por dentro.',      'ca' => 'Nuggets de pollastre cruixents, daurats i suculents per dins.',  'en' => 'Crispy chicken nuggets, golden and juicy inside.'],           'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689257/nuggets_d3iaeq.webp', 'price' => 4.50],
            ['category_id' => $tapas->id,        'name' => ['es' => 'Alitas de pollo crujiente', 'ca' => 'Aletes de pollastre cruixent', 'en' => 'Crispy Chicken Wings'],     'description' => ['es' => 'Alitas de pollo rebozadas con un crujiente dorado irresistible.',  'ca' => 'Aletes de pollastre arrebossades amb un cruixent daurat irresistible.', 'en' => 'Breaded chicken wings with an irresistible golden crunch.'],  'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689257/alitas_normales_kmueab.webp', 'price' => 5.00],
            ['category_id' => $tapas->id,        'name' => ['es' => 'Patatas fritas',            'ca' => 'Patates fregides',             'en' => 'French Fries'],             'description' => ['es' => 'Patatas fritas caseras crujientes con sal.',                      'ca' => 'Patates fregides casolanes cruixents amb sal.',              'en' => 'Crispy homemade french fries with salt.'],                    'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689256/fritas_eskt8e.webp', 'price' => 4.50],
            
            // Bebidas
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Fuze Tea',                  'ca' => 'Fuze Tea',                     'en' => 'Fuze Tea'],                 'description' => ['es' => 'Té helado con sabor a fruta, refrescante y suave.',               'ca' => 'Te gelat amb sabor a fruita, refrescant i suau.',            'en' => 'Iced fruit-flavoured tea, refreshing and smooth.'],           'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689260/fuzetea_gqsrzk.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Coca-Cola',                 'ca' => 'Coca-Cola',                    'en' => 'Coca-Cola'],                'description' => ['es' => 'Refresco de cola clásico bien frío.',                             'ca' => 'Refresc de cola clàssic ben fred.',                          'en' => 'Classic cold cola drink.'],                                   'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689261/cocacola_ebrqye.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Red Bull',                  'ca' => 'Red Bull',                     'en' => 'Red Bull'],                 'description' => ['es' => 'Bebida energética, te da alas.',                                  'ca' => 'Beguda energètica, et dóna ales.',                           'en' => 'Energy drink, gives you wings.'],                             'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689262/redbull_zero_kpngq3.webp', 'price' => 3.00],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Fanta Naranja',             'ca' => 'Fanta Taronja',                'en' => 'Fanta Orange'],             'description' => ['es' => 'Refresco de naranja con su sabor afrutado característico.',       'ca' => 'Refresc de taronja amb el seu sabor afruitat característic.', 'en' => 'Orange soft drink with its characteristic fruity flavour.'],  'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689262/fanta_naranja_ctznt7.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Fanta Limón',               'ca' => 'Fanta Llimona',                'en' => 'Fanta Lemon'],              'description' => ['es' => 'Refresco de limón refrescante y cítrico.',                        'ca' => 'Refresc de llimona refrescant i cítric.',                    'en' => 'Refreshing and citrusy lemon soft drink.'],                   'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689263/fanta_limon_dsm354.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Aquarius Naranja',          'ca' => 'Aquarius Taronja',             'en' => 'Aquarius Orange'],          'description' => ['es' => 'Bebida isotónica sabor naranja, repone energías.',                'ca' => 'Beguda isotònica sabor taronja, reposa energies.',           'en' => 'Orange-flavoured isotonic drink, restores energy.'],          'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689260/aquarius_naranja_bxfkif.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Aquarius Limón',            'ca' => 'Aquarius Llimona',             'en' => 'Aquarius Lemon'],           'description' => ['es' => 'Bebida isotónica sabor limón, repone energías.',                  'ca' => 'Beguda isotònica sabor llimona, reposa energies.',           'en' => 'Lemon-flavoured isotonic drink, restores energy.'],           'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689256/aquarius_limon_o5nkck.webp', 'price' => 2.50],
            ['category_id' => $bebidas->id,      'name' => ['es' => 'Agua pequeña',              'ca' => 'Aigua petita',                 'en' => 'Small Water'],              'description' => ['es' => 'Botella de agua mineral pequeña bien fría.',                      'ca' => "Ampolla d'aigua mineral petita ben freda.",                  'en' => 'Small cold mineral water bottle.'],                           'image' => 'https://res.cloudinary.com/drn7pgqbs/image/upload/v1773689264/agua_peque%C3%B1a_osjndr.webp', 'price' => 1.50],       
             ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['available' => true]));
        }
    }
}
