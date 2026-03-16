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
                ->get(),
        ]);
    }

    public function show(Order $order): Response
    {
        return Inertia::render('admin/Orders/Show', [
            'order' => $order->load(['user', 'items.product', 'payment']),
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
