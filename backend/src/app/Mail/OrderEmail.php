<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    // Had l-variable ghadi t-koun accessible directement f l-view Blade
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // T9der t-ziyd hta r-re9m dyal l-commande f l-unwan
            subject: 'Confirmation de votre commande #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Atteintion: tbe3 l-chemin li f resources/views
            view: 'emails.order_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
