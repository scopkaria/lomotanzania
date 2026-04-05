<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $newEmail,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Email Address Has Been Changed');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.email-changed-notification');
    }
}
