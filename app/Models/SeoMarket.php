<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class SeoMarket extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'target_country', 'source_market', 'title',
        'meta_title', 'meta_description', 'intro_content',
        'flights_info', 'visa_info', 'travel_tips', 'best_routes',
        'pricing_info', 'featured_image',
        'title_translations', 'intro_translations', 'is_published',
    ];

    protected $casts = [
        'title_translations' => 'array',
        'intro_translations' => 'array',
        'is_published'       => 'boolean',
    ];

    protected array $translatable = ['title', 'intro_content'];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get safaris for the target country.
     */
    public function getSafaris(int $limit = 12)
    {
        return SafariPackage::where('status', 'published')
            ->whereHas('countries', fn ($q) => $q->where('name', 'like', "%{$this->target_country}%"))
            ->latest()
            ->limit($limit)
            ->get();
    }
}
