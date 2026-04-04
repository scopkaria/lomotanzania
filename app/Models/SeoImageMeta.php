<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoImageMeta extends Model
{
    protected $table = 'seo_image_meta';

    protected $fillable = [
        'path', 'alt_text', 'seo_filename', 'caption',
        'is_optimized', 'original_size', 'optimized_size',
    ];

    protected $casts = [
        'is_optimized'   => 'boolean',
        'original_size'  => 'integer',
        'optimized_size' => 'integer',
    ];

    /**
     * Get compression savings percentage.
     */
    public function getSavingsAttribute(): ?float
    {
        if (!$this->original_size || !$this->optimized_size) {
            return null;
        }
        return round((1 - $this->optimized_size / $this->original_size) * 100, 1);
    }
}
