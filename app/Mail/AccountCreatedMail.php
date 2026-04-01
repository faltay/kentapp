<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $mailLocale;

    public function __construct(
        public User $user,
        public string $plainPassword,
    ) {
        $this->mailLocale = app()->getLocale();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.account_created.subject', ['app_name' => config('app.name')], $this->mailLocale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-created',
            with: [
                'locale' => $this->mailLocale,
            ],
        );
    }
}
