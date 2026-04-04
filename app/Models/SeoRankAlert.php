<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoRankAlert extends Model
{
    protected $fillable = [
        'seo_ranking_id', 'type', 'old_position', 'new_position', 'is_read',
    ];

    protected $casts = [
        'is_read'      => 'boolean',
        'old_position' => 'integer',
        'new_position' => 'integer',
    ];

    public function ranking(): BelongsTo
    {
        return $this->belongsTo(SeoRanking::class, 'seo_ranking_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Human-readable description.
     */
    public function getDescriptionAttribute(): string
    {
        return match ($this->type) {
            'drop' => "Dropped from #{$this->old_position} → #{$this->new_position}",
            'gain' => "Improved from #{$this->old_position} → #{$this->new_position}",
            'new_ranking' => "New ranking at #{$this->new_position}",
            'lost' => "Lost ranking (was #{$this->old_position})",
            default => "Position changed",
        };
    }
}
