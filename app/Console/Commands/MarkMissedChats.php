<?php

namespace App\Console\Commands;

use App\Models\ChatSession;
use Illuminate\Console\Command;

class MarkMissedChats extends Command
{
    protected $signature = 'chat:mark-missed';
    protected $description = 'Mark inactive chat sessions as missed if no agent responded';

    public function handle(): int
    {
        $count = ChatSession::where('status', 'active')
            ->where('last_activity_at', '<', now()->subMinutes(15))
            ->whereDoesntHave('messages', fn ($q) => $q->where('sender_type', 'agent'))
            ->update(['status' => 'missed']);

        $this->info("Marked {$count} chats as missed.");
        return self::SUCCESS;
    }
}
