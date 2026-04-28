<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'total_orders'   => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'total_products' => Product::count(),
                'total_clients'  => User::where('role', 'client')->count(),
                'revenue'        => Order::whereNotIn('status', ['cancelled'])->sum('total_price'),
            ],
            'recent_orders' => Order::with('user')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn ($order) => [
                    'id'          => $order->id,
                    'user'        => $order->user?->name ?? 'Desconocido',
                    'total_price' => $order->total_price,
                    'status'      => $order->status,
                    'created_at'  => $order->created_at->format('d/m/Y H:i'),
                ]),
        ]);
    }
}
