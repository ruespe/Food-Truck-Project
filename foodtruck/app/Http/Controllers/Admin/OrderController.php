<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderReadyClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->client . '%')
                    ->orWhere('email', 'like', '%' . $request->client . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $paginator = $query->paginate(15)->withQueryString();

        $paginator->getCollection()->transform(fn($order) => array_merge($order->toArray(), [
            'created_at' => $order->created_at->format('d/m/Y H:i'),
            'updated_at' => $order->updated_at->format('d/m/Y H:i'),
            'ref'        => 'FT-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
        ]));

        return Inertia::render('admin/Orders/Index', [
            'orders'   => $paginator,
            'filters'  => $request->only(['status', 'client', 'date_from', 'date_to']),
            'statuses' => ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'],
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
                'items'          => $order->items->map(fn($item) => [
                    'id'      => $item->id,
                    'quantity' => $item->quantity,
                    'price'   => $item->price,
                    'product' => [
                        'id'          => $item->product?->id,
                        'name'        => $item->product?->name ?? ['es' => 'Producto eliminado', 'ca' => 'Producte eliminat', 'en' => 'Deleted product'],
                        'description' => $item->product?->description ?? '',
                        'image'       => $item->product?->image,
                    ],
                ]),
                'payment' => $order->payment ? [
                    'status'         => $order->payment->status,
                    'transaction_id' => $order->payment->transaction_id,
                ] : null,
            ],
            'statuses' => ['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        if ($request->status === 'ready') {
            $order->load(['user', 'items.product']);
            Mail::to($order->user->email)->send(new OrderReadyClient($order));
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
