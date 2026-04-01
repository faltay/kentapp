<?php

namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $mailLocale;

    public function __construct(
        public Restaurant $restaurant,
        public float $amount,
        public string $currency,
    ) {
        $this->mailLocale = app()->getLocale();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.payment_failed.subject', [], $this->mailLocale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failed',
            with: [
                'locale' => $this->mailLocale,
            ],
        );
    }
}
