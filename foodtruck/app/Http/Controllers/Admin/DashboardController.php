<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Ingresos por día — últimos 14 días
        $revenueByDay = Order::whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Rellenar días sin ventas con 0
        $days = collect();
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $days[$date] = round((float) ($revenueByDay[$date] ?? 0), 2);
        }

        // Productos más vendidos — top 8 (excluir productos eliminados)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name')
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(8)
            ->get()
            ->map(fn($item) => [
                'name'       => $item->product?->name ?? ['es' => 'Eliminado', 'ca' => 'Eliminat', 'en' => 'Deleted'],
                'total_sold' => (int) $item->total_sold,
            ]);

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'total_orders'    => Order::count(),
                'confirmed_orders' => Order::where('status', 'confirmed')->count(),
                'total_products'  => Product::count(),
                'total_clients'   => User::where('role', 'client')->count(),
                'pending_orders'  => Order::where('status', 'delivered')->count(),
                'revenue'         => Order::whereNotIn('status', ['cancelled'])->sum('total_price'),
            ],
            'revenue_chart' => [
                'labels' => $days->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values(),
                'data'   => $days->values(),
            ],
            'top_products' => $topProducts,
            'recent_orders' => Order::with('user')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($order) => [
                    'id'          => $order->id,
                    'user'        => $order->user?->name ?? 'Desconocido',
                    'total_price' => $order->total_price,
                    'status'      => $order->status,
                    'created_at'  => $order->created_at->format('d/m/Y H:i'),
                ]),
            'today_location' => Location::whereDate('date', today())->first(),
        ]);
    }

    public function saveLocation(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'start_time' => ['required', function ($attr, $val, $fail) {
                if ($val < '19:00') $fail('La apertura debe ser a partir de las 19:00.');
            }],
            'end_time'   => ['required', function ($attr, $val, $fail) {
                if ($val > '07:00' && $val < '19:00') $fail('El cierre debe ser antes de las 07:00 (madrugada).');
            }],
        ]);
        $data['date'] = today()->toDateString();

        Location::updateOrCreate(['date' => $data['date']], $data);

        return back()->with('success', 'Ubicación de hoy guardada.');
    }
}
