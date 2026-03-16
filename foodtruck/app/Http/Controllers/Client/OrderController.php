<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.product'])
            ->latest()
            ->get();

        return Inertia::render('client/OrderHistory', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Recalcular el total en servidor para evitar manipulaciones
        $total = 0;
        $lines = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['id']);
            abort_unless($product->available, 422, 'Producto no disponible.');

            $lines[] = [
                'product'  => $product,
                'quantity' => $item['quantity'],
                'price'    => $product->price,
            ];
            $total += $product->price * $item['quantity'];
        }

        $order = Order::create([
            'user_id'        => auth()->id(),
            'total_price'    => $total,
            'status'         => 'pending',
            'payment_method' => 'stripe',
        ]);

        foreach ($lines as $line) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $line['product']->id,
                'quantity'   => $line['quantity'],
                'price'      => $line['price'],
            ]);
        }

        // Redirigir al checkout con el ID del pedido
        return redirect()->route('checkout', ['order' => $order->id]);
    }

    public function show(Order $order): Response
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return Inertia::render('client/OrderDetail', [
            'order' => $order->load(['items.product', 'payment']),
        ]);
    }
}
