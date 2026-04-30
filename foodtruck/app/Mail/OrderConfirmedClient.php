<?php

namespace App\Mail;

use App\Models\Location;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmedClient extends Mailable
{
    use Queueable, SerializesModels;

    public ?Location $location;

    public function __construct(public Order $order)
    {
        $this->location = Location::whereDate('date', today())->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu pedido en FoodTruck ha sido confirmado! 🍔 #FT-' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order_confirmed_client');
    }
}
