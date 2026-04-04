<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_type',
        'order',
        'is_active',
        'data',
    ];

    protected $casts = [
        'data'      => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function heroSlides(): HasMany
    {
        return $this->hasMany(HeroSlide::class)->orderBy('order');
    }

    /**
     * Get a translated value from the data JSON.
     */
    public function getData(string $key, ?string $locale = null, $default = '')
    {
        $locale = $locale ?: app()->getLocale();
        $data = $this->data ?? [];

        if (isset($data[$key]) && is_array($data[$key])) {
            return $data[$key][$locale] ?? $data[$key]['en'] ?? $default;
        }

        return $data[$key] ?? $default;
    }
}
