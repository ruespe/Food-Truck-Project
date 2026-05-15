<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'name'    => 'María García',
                'email'   => 'maria@example.com',
                'rating'  => 5,
                'comment' => '¡Increíble! Las hamburguesas son las mejores que he probado en mucho tiempo. El pan estaba perfecto y la carne muy jugosa.',
            ],
            [
                'name'    => 'Carlos López',
                'email'   => 'carlos@example.com',
                'rating'  => 5,
                'comment' => 'Pedí los bocadillos y quedé encantado. Precio muy razonable para la calidad que ofrecen. Repetiré seguro.',
            ],
            [
                'name'    => 'Ana Martínez',
                'email'   => 'ana@example.com',
                'rating'  => 4,
                'comment' => 'Muy buena comida y atención rápida. Me encantó que todo fuera fresco. El ambiente del food truck es muy chulo.',
            ],
            [
                'name'    => 'Pablo Sánchez',
                'email'   => 'pablo@example.com',
                'rating'  => 5,
                'comment' => 'El mejor food truck de la zona sin duda. Las tapas estaban deliciosas y las raciones son muy generosas.',
            ],
            [
                'name'    => 'Laura Fernández',
                'email'   => 'laura@example.com',
                'rating'  => 5,
                'comment' => 'Fui con mis amigos y todos quedamos muy satisfechos. La app es muy cómoda para pedir. Totalmente recomendado.',
            ],
            [
                'name'    => 'Jordi Puig',
                'email'   => 'jordi@example.com',
                'rating'  => 4,
                'comment' => 'Molt bona qualitat i un tracte excel·lent. La comanda va arribar molt ràpid i tot estava calent. Tornarem aviat!',
            ],
        ];

        foreach ($reviews as $data) {
            $user = User::where('email', $data['email'])->first();
            if (! $user) {
                continue;
            }

            Review::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'rating'  => $data['rating'],
                    'comment' => $data['comment'],
                    'visible' => true,
                ]
            );
        }
    }
}
