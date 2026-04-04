<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoRanking extends Model
{
    protected $fillable = [
        'keyword', 'url', 'position', 'previous_position',
        'search_engine', 'locale', 'history', 'last_checked_at',
    ];

    protected $casts = [
        'history'          => 'array',
        'last_checked_at'  => 'datetime',
        'position'         => 'integer',
        'previous_position' => 'integer',
    ];

    /**
     * Get position change (positive = improved, negative = dropped).
     */
    public function getChangeAttribute(): ?int
    {
        if (is_null($this->previous_position) || is_null($this->position)) {
            return null;
        }
        return $this->previous_position - $this->position;
    }

    /**
     * Change label: improved, dropped, or stable.
     */
    public function getTrendAttribute(): string
    {
        $change = $this->change;
        if (is_null($change)) return 'new';
        if ($change > 0) return 'improved';
        if ($change < 0) return 'dropped';
        return 'stable';
    }

    public function scopeDropped($query)
    {
        return $query->whereColumn('position', '>', 'previous_position');
    }

    public function scopeImproved($query)
    {
        return $query->whereColumn('position', '<', 'previous_position');
    }

    /**
     * Add a history entry.
     */
    public function recordPosition(int $position): void
    {
        $history = $this->history ?? [];
        $history[] = [
            'date'     => now()->toDateString(),
            'position' => $position,
        ];

        // Keep only last 90 days
        $history = array_slice($history, -90);

        $this->update([
            'previous_position' => $this->position,
            'position'          => $position,
            'history'           => $history,
            'last_checked_at'   => now(),
        ]);
    }
}
