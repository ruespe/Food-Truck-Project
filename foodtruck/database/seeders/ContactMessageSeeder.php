<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name'    => 'María García',
                'email'   => 'maria@example.com',
                'message' => '¿A qué hora estaréis en la Barceloneta el próximo fin de semana?',
                'read'    => true,
            ],
            [
                'name'    => 'Carlos López',
                'email'   => 'carlos@example.com',
                'message' => 'Me encantó la hamburguesa que pedí el otro día, ¡seguiré repitiendo!',
                'read'    => true,
            ],
            [
                'name'    => 'Ana Martínez',
                'email'   => 'ana@example.com',
                'message' => 'Tengo intolerancia al gluten, ¿tenéis opciones sin gluten en el menú?',
                'read'    => true,
            ],
            [
                'name'    => 'Pablo Sánchez',
                'email'   => 'pablo@example.com',
                'message' => '¿Podéis hacer catering para una fiesta privada de unas 50 personas?',
                'read'    => false,
            ],
            [
                'name'    => 'Laura Fernández',
                'email'   => 'laura@example.com',
                'message' => 'El pedido me llegó incompleto, faltaban las patatas fritas. ¿Cómo puedo reclamar?',
                'read'    => false,
            ],
            [
                'name'    => 'Jordi Puig',
                'email'   => 'jordi@example.com',
                'message' => 'Voleu participar a la Fira de Gràcia d\'aquest estiu? Seria genial tenir-vos allà.',
                'read'    => false,
            ],
            [
                'name'    => 'Marta Soler',
                'email'   => 'marta@example.com',
                'message' => '¿Aceptáis pagos con Bizum? Sería muy cómodo para los clientes.',
                'read'    => false,
            ],
            [
                'name'    => 'Pau Rovira',
                'email'   => 'pau@example.com',
                'message' => 'He probado vuestros nuggets y son los mejores que he comido. ¡Enhorabuena al equipo!',
                'read'    => false,
            ],
        ];

        foreach ($messages as $message) {
            ContactMessage::create($message);
        }
    }
}
