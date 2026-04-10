<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexHeroImage extends Model
{
    protected $fillable = [
        'section_key',
        'label',
        'image_path',
        'title',
        'subtitle',
    ];

    /**
     * Retrieve the hero record for a given section key.
     */
    public static function forSection(string $key): ?static
    {
        return static::where('section_key', $key)->first();
    }

    /**
     * Return the public URL for the hero image, or null if none set.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
