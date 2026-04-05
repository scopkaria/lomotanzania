<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    protected $fillable = [
        'user_id', 'visitor_id', 'visitor_name', 'visitor_email',
        'visitor_ip', 'user_agent', 'status', 'assigned_to',
        'transferred_from', 'transfer_note', 'department_id',
        'page_history', 'current_page', 'last_activity_at',
    ];

    protected $casts = [
        'page_history' => 'array',
        'last_activity_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function transferredFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_from');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function unreadMessages()
    {
        return $this->messages()->where('is_read', false)->where('sender_type', 'visitor');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMissed($query)
    {
        return $query->where('status', 'missed');
    }
}
