<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_session_id', 'user_id', 'sender_type', 'message_type', 'whisper_to', 'message', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function whisperRecipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whisper_to');
    }

    public function scopeVisible($query, $userId = null)
    {
        // Super admin sees ALL messages including all whispers
        $user = $userId ? \App\Models\User::find($userId) : null;
        if ($user && $user->isSuperAdmin()) {
            return $query;
        }

        return $query->where(function ($q) use ($userId) {
            $q->where('message_type', 'normal')
              ->orWhere(function ($q2) use ($userId) {
                  $q2->where('message_type', 'whisper')
                     ->where(function ($q3) use ($userId) {
                         $q3->where('user_id', $userId)
                            ->orWhere('whisper_to', $userId)
                            ->orWhereNull('whisper_to');
                     });
              })
              ->orWhere('message_type', 'system');
        });
    }
}
