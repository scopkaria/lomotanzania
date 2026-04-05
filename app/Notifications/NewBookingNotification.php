<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public array $data) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Booking',
            'message' => ($this->data['client_name'] ?? 'A client') . ' made a booking',
            'url' => $this->data['url'] ?? route('admin.bookings.index'),
            'icon' => 'booking',
        ];
    }
}
