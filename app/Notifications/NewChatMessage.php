<?php

namespace App\Notifications;

use App\Models\ChatSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(public ChatSession $session) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Chat Message',
            'message' => ($this->session->visitor_name ?: 'A visitor') . ' sent a message',
            'url' => route('admin.chat.show', $this->session),
            'icon' => 'chat',
            'session_id' => $this->session->id,
        ];
    }
}
