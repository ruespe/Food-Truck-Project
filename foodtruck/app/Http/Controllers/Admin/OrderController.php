<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Orders/Index', [
            'orders' => Order::with(['user', 'items.product'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load(['user', 'items.product', 'payment']);

        return Inertia::render('admin/Orders/Show', [
            'order' => [
                'id'             => $order->id,
                'total_price'    => $order->total_price,
                'status'         => $order->status,
                'payment_method' => $order->payment_method,
                'created_at'     => $order->created_at->format('d/m/Y H:i'),
                'updated_at'     => $order->updated_at->format('d/m/Y H:i'),
                'user'           => $order->user?->only('id', 'name', 'email'),
                'items'          => $order->items->map(fn ($item) => [
                    'id'      => $item->id,
                    'quantity' => $item->quantity,
                    'price'   => $item->price,
                    'product' => [
                        'id'          => $item->product?->id,
                        'name'        => $item->product?->name ?? 'Producto eliminado',
                        'description' => $item->product?->description ?? '',
                        'image'       => $item->product?->image,
                    ],
                ]),
                'payment' => $order->payment ? [
                    'status'         => $order->payment->status,
                    'transaction_id' => $order->payment->transaction_id,
                ] : null,
            ],
            'statuses' => ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
