<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    protected $fillable = [
        'keyword', 'intent', 'volume', 'difficulty',
        'target_url', 'country', 'group', 'priority',
    ];

    protected $casts = [
        'volume'     => 'integer',
        'difficulty' => 'integer',
        'priority'   => 'integer',
    ];

    public function scopeByIntent($query, string $intent)
    {
        return $query->where('intent', $intent);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 70);
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match (true) {
            $this->difficulty <= 30 => 'Easy',
            $this->difficulty <= 60 => 'Medium',
            $this->difficulty <= 80 => 'Hard',
            default                 => 'Very Hard',
        };
    }
}
