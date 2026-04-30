<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\OrderConfirmedClient;
use App\Mail\OrderConfirmedAdmin;
use App\Models\Order;

// ⚠️ Solo para desarrollo — eliminar antes de producción
if (app()->environment('local')) {

    Route::get('/test-mail/client/{order}', function (Order $order) {
        $order->load(['items.product', 'user']);
        Mail::to($order->user->email)->send(new OrderConfirmedClient($order));
        return '✅ Email de cliente enviado a ' . $order->user->email;
    })->middleware(['auth']);

    Route::get('/test-mail/admin/{order}', function (Order $order) {
        $order->load(['items.product', 'user']);
        $adminEmail = config('mail.admin_address');
        Mail::to($adminEmail)->send(new OrderConfirmedAdmin($order));
        return '✅ Email de admin enviado a ' . $adminEmail;
    })->middleware(['auth']);

}
