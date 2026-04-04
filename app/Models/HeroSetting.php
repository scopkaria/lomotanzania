<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = [
        'background_video',
        'video_poster',
        'overlay_opacity',
        'autoplay',
        'transition_speed',
    ];

    protected function casts(): array
    {
        return [
            'overlay_opacity' => 'decimal:2',
            'autoplay'        => 'boolean',
            'transition_speed' => 'integer',
        ];
    }

    /**
     * Get the singleton hero settings row, creating if needed.
     */
    public static function instance(): static
    {
        return static::firstOrCreate([]);
    }
}
