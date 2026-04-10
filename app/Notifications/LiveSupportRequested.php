<?php

namespace App\Notifications;

use App\Models\ChatSession;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LiveSupportRequested extends Notification
{
    use Queueable;

    public function __construct(
        public ChatSession $session,
        public Department $department
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => '🔔 Live Support Requested',
            'message' => ($this->session->visitor_name ?: 'A visitor') . ' wants live support from ' . $this->department->name . ' department',
            'url' => route('admin.chat.show', $this->session),
            'icon' => 'support',
            'session_id' => $this->session->id,
            'department_id' => $this->department->id,
            'department_name' => $this->department->name,
        ];
    }
}
