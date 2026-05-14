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
    public function index(Request $request): Response
    {
        $filter = $request->query('filter', 'all'); // all | active | done

        $query = auth()->user()
            ->orders()
            ->with(['items.product'])
            ->latest();

        if ($filter === 'active') {
            $query->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
        } elseif ($filter === 'done') {
            $query->whereIn('status', ['delivered', 'cancelled']);
        }

        $paginator = $query->paginate(10)->withQueryString();

        $paginator->getCollection()->transform(fn($order) => array_merge($order->toArray(), [
            'created_at' => $order->created_at->format('d/m/Y H:i'),
            'ref'        => 'FT-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
        ]));

        return Inertia::render('client/OrderHistory', [
            'orders' => $paginator,
            'filter' => $filter,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Verificar que el truck está abierto ahora (los admins pueden pedir siempre)
        $location = \App\Models\Location::whereDate('date', today())->first();
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();
        if ($location && !$authUser->isAdmin()) {
            $now   = now()->format('H:i');
            $start = substr($location->start_time, 0, 5);
            $end   = substr($location->end_time, 0, 5);
            // Rango cruza medianoche (ej. 19:00 – 07:00)
            $isOpen = $start > $end
                ? ($now >= $start || $now <= $end)
                : ($now >= $start && $now <= $end);
            if (!$isOpen) {
                $messages = [
                    'es' => "El food truck está cerrado ahora. Horario de hoy: {$start} – {$end}.",
                    'ca' => "El food truck està tancat ara. Horari d'avui: {$start} – {$end}.",
                    'en' => "The food truck is closed now. Today's schedule: {$start} – {$end}.",
                ];
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'closed' => $messages[app()->getLocale()] ?? $messages['en'],
                ]);
            }
        }

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

        $order->load(['items.product', 'payment']);

        return Inertia::render('client/OrderDetail', [
            'order' => array_merge($order->toArray(), [
                'created_at'     => $order->created_at->format('d/m/Y H:i'),
                'updated_at'     => $order->updated_at->format('d/m/Y H:i'),
                'ref'            => 'FT-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                'payment_method' => $order->payment_method ?? 'stripe',
            ]),
        ]);
    }

    public function destroy(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'pending', 422, 'Solo se pueden cancelar pedidos pendientes.');

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', 'Pedido cancelado correctamente.');
    }
}
