<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\OrderConfirmedClient;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear una Stripe Checkout Session para el pedido dado.
     */
    public function createSession(Order $order): HttpResponse|RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'pending', 422, 'El pedido ya fue procesado.');

        $order->load('items.product');

        $lineItems = $order->items->map(fn($item) => [
            'price_data' => [
                'currency'     => 'eur',
                'product_data' => [
                    'name' => is_array($item->product->name) ? ($item->product->name['es'] ?? $item->product->name['en'] ?? 'Producto') : $item->product->name,
                ],
                'unit_amount'  => (int) round($item->price * 100), // céntimos
            ],
            'quantity' => $item->quantity,
        ])->values()->toArray();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('payment.success', ['order' => $order->id]),
            'cancel_url'           => route('payment.cancel',  ['order' => $order->id]),
            'metadata'             => ['order_id' => $order->id],
        ]);

        // Guardar el transaction_id (session ID de Stripe) antes de redirigir
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_provider' => 'stripe',
                'transaction_id'   => $session->id,
                'status'           => 'pending',
                'amount'           => $order->total_price,
            ]
        );

        return Inertia::location($session->url);
    }

    /**
     * Página de éxito tras el pago.
     * Stripe solo redirige aquí si el pago fue aceptado, así que
     * actualizamos el estado de forma optimista. El webhook hará
     * lo mismo cuando llegue (operación idempotente).
     */
    public function success(Order $order): Response
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
            Payment::where('order_id', $order->id)->update(['status' => 'paid']);

            // Enviar emails directamente (el webhook de Stripe no llega a localhost en desarrollo)
            $order->load(['items.product', 'user']);
            Mail::to($order->user->email)->send(new OrderConfirmedClient($order));
        }

        return Inertia::render('client/PaymentSuccess', [
            'order' => $order->load(['items.product', 'payment']),
        ]);
    }

    /**
     * Pago cancelado: el pedido nunca existió, se elimina.
     */
    public function cancel(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->delete(); // cascade elimina items y payment

        return redirect('/menu')->with('info', 'Pago cancelado.');
    }

    /**
     * Webhook de Stripe para confirmar el pago.
     */
    public function webhook(Request $request): \Illuminate\Http\Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Webhook error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update(['status' => 'confirmed']);
                    Payment::where('order_id', $orderId)->update(['status' => 'paid']);

                    $order->load(['items.product', 'user']);

                    // Encolar email al cliente (se envía de forma asíncrona)
                    Mail::to($order->user->email)
                        ->later(now()->addSeconds(2), new OrderConfirmedClient($order));

                    \Illuminate\Support\Facades\Log::info('Email encolado — pedido #' . $order->id);
                }
            }
        }

        return response('OK', 200);
    }
}
