<?php

namespace App\Mail;

use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionStartedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $mailLocale;

    public function __construct(
        public Restaurant $restaurant,
        public Subscription $subscription,
        public SubscriptionPlan $plan,
    ) {
        $this->mailLocale = app()->getLocale();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.subscription_started.subject', [], $this->mailLocale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-started',
            with: [
                'locale' => $this->mailLocale,
            ],
        );
    }
}
