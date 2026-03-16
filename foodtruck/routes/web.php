<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// ─── Pública ─────────────────────────────────────────────────────────────────

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/menu', [Client\MenuController::class, 'index'])->name('menu');
Route::get('/menu/{product}', [Client\MenuController::class, 'show'])->name('menu.show');
Route::post('/contact', [Client\ContactController::class, 'store'])->name('contact.store');

// ─── Stripe webhook (sin CSRF) ────────────────────────────────────────────────

Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.stripe');

// ─── Cliente (autenticado) ────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    // Historial y detalle de pedidos
    Route::get('/orders', [Client\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [Client\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [Client\OrderController::class, 'show'])->name('orders.show');

    // Checkout con Stripe
    Route::get('/checkout/{order}', [PaymentController::class, 'createSession'])->name('checkout');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// ─── Administrador ────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::resource('products', Admin\ProductController::class);

    // Categorías
    Route::resource('categories', Admin\CategoryController::class);

    // Pedidos
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    // Ubicación
    Route::get('locations', [Admin\LocationController::class, 'index'])->name('locations.index');
    Route::post('locations', [Admin\LocationController::class, 'store'])->name('locations.store');
    Route::patch('locations/{location}', [Admin\LocationController::class, 'update'])->name('locations.update');
    Route::delete('locations/{location}', [Admin\LocationController::class, 'destroy'])->name('locations.destroy');
});

require __DIR__.'/settings.php';
