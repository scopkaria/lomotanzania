<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorProfile extends Model
{
    protected $fillable = [
        'user_id', 'name', 'title', 'photo', 'bio',
        'linkedin_url', 'twitter_url', 'expertise', 'years_experience', 'is_active',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'years_experience' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
