<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $clients  = User::where('role', 'client')->get();
        $cliente  = User::where('email', 'cliente@foodtruck.com')->first();

        $statuses       = ['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];
        $paymentMethods = ['stripe', 'cash'];
        $testing        = User::where('email', 'testing@foodtruck.com')->first();

        // 20 pedidos para testing@foodtruck.com
        if ($testing) {
            for ($i = 0; $i < 20; $i++) {
                $this->createOrder(
                    $testing,
                    $products,
                    $statuses[array_rand($statuses)],
                    'stripe',
                    rand(0, 90)
                );
            }
        }

        // Pedidos fijos para cliente@foodtruck.com
        $clienteOrders = [
            ['status' => 'delivered', 'payment_method' => 'stripe', 'days_ago' => 30],
            ['status' => 'delivered', 'payment_method' => 'cash',   'days_ago' => 20],
            ['status' => 'delivered', 'payment_method' => 'stripe', 'days_ago' => 14],
            ['status' => 'delivered', 'payment_method' => 'stripe', 'days_ago' => 10],
            ['status' => 'cancelled', 'payment_method' => 'stripe', 'days_ago' => 7],
            ['status' => 'preparing', 'payment_method' => 'cash',   'days_ago' => 1],
            ['status' => 'confirmed', 'payment_method' => 'stripe', 'days_ago' => 0],
        ];

        foreach ($clienteOrders as $data) {
            $this->createOrder($cliente, $products, $data['status'], $data['payment_method'], $data['days_ago']);
        }

        // Pedidos aleatorios para el resto de clientes
        foreach ($clients->where('email', '!=', 'cliente@foodtruck.com') as $user) {
            $numOrders = rand(1, 3);
            for ($i = 0; $i < $numOrders; $i++) {
                $this->createOrder(
                    $user,
                    $products,
                    $statuses[array_rand($statuses)],
                    $paymentMethods[array_rand($paymentMethods)],
                    rand(1, 60)
                );
            }
        }
    }

    private function createOrder(User $user, $products, string $status, string $paymentMethod, int $daysAgo): void
    {
        $createdAt = Carbon::now()->subDays($daysAgo);

        $order = Order::create([
            'user_id'        => $user->id,
            'total_price'    => 0,
            'status'         => $status,
            'payment_method' => $paymentMethod,
            'created_at'     => $createdAt,
            'updated_at'     => $createdAt,
        ]);

        $selectedProducts = $products->random(rand(1, min(4, $products->count())));
        $total = 0;

        foreach ($selectedProducts as $product) {
            $quantity = rand(1, 3);
            $price    = $product->price;

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $price,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $total += $price * $quantity;
        }

        $order->update(['total_price' => $total]);

        // Pago Stripe si procede
        if ($paymentMethod === 'stripe' && $status !== 'cancelled') {
            Payment::create([
                'order_id'         => $order->id,
                'payment_provider' => 'stripe',
                'transaction_id'   => 'pi_' . Str::random(24),
                'status'           => 'paid',
                'amount'           => $total,
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);
        }
    }
}
