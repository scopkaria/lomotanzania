<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification
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
            'title' => $this->data['title'] ?? 'New Request',
            'message' => $this->data['message'] ?? 'You have a new request',
            'url' => $this->data['url'] ?? '#',
            'icon' => $this->data['icon'] ?? 'request',
        ];
    }
}
