<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlide extends Model
{
    protected $fillable = [
        'page_section_id',
        'label',
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_link',
        'next_up_text',
        'bg_color',
        'bg_image',
        'image_alt',
        'order',
    ];

    protected $casts = [
        'label'        => 'array',
        'title'        => 'array',
        'subtitle'     => 'array',
        'button_text'  => 'array',
        'next_up_text' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }

    /**
     * Get a translated field value.
     */
    public function translated(string $field, ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $value = $this->{$field};

        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? '';
        }

        return $value ?? '';
    }
}
