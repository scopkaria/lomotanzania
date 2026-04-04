<?php

namespace App\Mail;

use App\Models\SafariPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SafariPlanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SafariPlan $plan) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Safari Plan from ' . $this->plan->first_name . ' ' . $this->plan->last_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.safari-plan-notification',
        );
    }
}
