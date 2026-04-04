<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoLinkRule extends Model
{
    protected $fillable = ['keyword', 'url', 'anchor_text', 'priority', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'priority'  => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the anchor text to use (keyword if no custom anchor set).
     */
    public function getAnchorAttribute(): string
    {
        return $this->anchor_text ?: $this->keyword;
    }
}
