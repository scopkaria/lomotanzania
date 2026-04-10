<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ADDED: Blog comment model with admin approval workflow
class BlogComment extends Model
{
    protected $fillable = [
        'post_id',
        'name',
        'email',
        'phone',
        'body',
        'status',
        'honeypot',
        'ip_address',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
