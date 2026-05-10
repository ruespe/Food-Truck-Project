<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Http\Controllers\PaymentController;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// ─── Pública ─────────────────────────────────────────────────────────────────

Route::get('/', function () {
    $location = Location::whereDate('date', today())->first() ?? new Location([
        'name'       => 'Mataró, Barcelona',
        'latitude'   => 41.5336796,
        'longitude'  => 2.4377341,
        'start_time' => '12:00:00',
        'end_time'   => '22:00:00',
    ]);

    return inertia('Welcome', [
        'canRegister'      => Features::enabled(Features::registration()),
        'featuredProducts' => Product::with('category')
            ->where('available', true)
            ->inRandomOrder()
            ->take(6)
            ->get(['id', 'category_id', 'name', 'description', 'price', 'image']),
        'location'         => $location,
    ]);
})->name('home');

Route::get('/menu', [Client\MenuController::class, 'index'])->name('menu');
Route::get('/menu/{product}', [Client\MenuController::class, 'show'])->name('menu.show');
Route::post('/contact', [Client\ContactController::class, 'store'])->name('contact.store');
Route::inertia('/cart', 'client/Cart')->name('cart');

// ─── Avisos legales ───────────────────────────────────────────────────────────

Route::inertia('/legal/privacidad', 'legal/Privacy')->name('legal.privacy');
Route::inertia('/legal/terminos', 'legal/Terms')->name('legal.terms');
Route::inertia('/legal/cookies', 'legal/Cookies')->name('legal.cookies');

// ─── Stripe webhook (sin CSRF) ────────────────────────────────────────────────

Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.stripe');

// ─── Cliente (autenticado) ────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        $user = Auth::user();
        // Limpiar la URL "intended" para evitar que Fortify la use en el próximo login
        session()->forget('url.intended');
        if ($user->role === 'admin') {
            return redirect('/admin/');
        }
        // Los clientes van a la landing page
        return redirect('/');
    })->name('dashboard');

    Route::post('dashboard/location', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);
        $data['date'] = today()->toDateString();
        Location::updateOrCreate(['date' => $data['date']], $data);
        return back()->with('success', 'Ubicación guardada.');
    })->middleware('admin')->name('dashboard.location');

    // Historial y detalle de pedidos
    Route::get('/orders', [Client\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [Client\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [Client\OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [Client\OrderController::class, 'destroy'])->name('orders.destroy');

    // Checkout con Stripe
    Route::get('/checkout/{order}', [PaymentController::class, 'createSession'])->name('checkout');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// ─── Administrador ────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/location', [Admin\DashboardController::class, 'saveLocation'])->name('dashboard.location');

    // Productos
    Route::resource('products', Admin\ProductController::class);
    Route::patch('products/{product}/toggle-stock', [Admin\ProductController::class, 'toggleStock'])->name('admin.products.toggle-stock');

    // Categorías
    Route::resource('categories', Admin\CategoryController::class);

    // Pedidos
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    // Ubicación
    Route::get('locations', [Admin\LocationController::class, 'index'])->name('locations.index');
    Route::post('locations', [Admin\LocationController::class, 'store'])->name('locations.store');
    Route::patch('locations/{location}', [Admin\LocationController::class, 'update'])->name('locations.update');
    Route::delete('locations/{location}', [Admin\LocationController::class, 'destroy'])->name('locations.destroy');

    // Mensajes de contacto
    Route::get('contact', [Admin\ContactMessageController::class, 'index'])->name('contact.index');
    Route::patch('contact/{contactMessage}/read', [Admin\ContactMessageController::class, 'markRead'])->name('contact.read');
    Route::delete('contact/{contactMessage}', [Admin\ContactMessageController::class, 'destroy'])->name('contact.destroy');

    // Gestión de usuarios
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::patch('users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/settings.php';
