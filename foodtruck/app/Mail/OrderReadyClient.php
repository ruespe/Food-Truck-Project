<?php

namespace App\Mail;

use App\Models\Location;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReadyClient extends Mailable
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
            subject: '¡Tu pedido está listo para recoger! #FT-' . str_pad($this->order->id, 4, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order_ready_client');
    }
}
