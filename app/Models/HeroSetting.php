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
        'hero_safari_ids',
        'button_text',
        'button_link',
    ];

    protected function casts(): array
    {
        return [
            'overlay_opacity'  => 'decimal:2',
            'autoplay'         => 'boolean',
            'transition_speed' => 'integer',
            'hero_safari_ids'  => 'array',
            'button_text'      => 'array',
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
