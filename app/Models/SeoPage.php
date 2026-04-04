<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'type', 'title', 'meta_title', 'meta_description', 'meta_keywords',
        'intro_content', 'body_content', 'featured_image', 'filter_criteria',
        'title_translations', 'intro_translations', 'body_translations',
        'is_auto_generated', 'is_published', 'views',
    ];

    protected $casts = [
        'filter_criteria'     => 'array',
        'title_translations'  => 'array',
        'intro_translations'  => 'array',
        'body_translations'   => 'array',
        'is_auto_generated'   => 'boolean',
        'is_published'        => 'boolean',
    ];

    protected array $translatable = ['title', 'intro_content', 'body_content'];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get matching safaris based on filter criteria.
     */
    public function getMatchingSafaris(int $limit = 12)
    {
        $criteria = $this->filter_criteria ?? [];
        $query = SafariPackage::where('status', 'published');

        if (!empty($criteria['country_slug'])) {
            $query->whereHas('countries', fn ($q) => $q->where('slug', $criteria['country_slug']));
        }
        if (!empty($criteria['destination_slug'])) {
            $query->whereHas('destinations', fn ($q) => $q->where('slug', $criteria['destination_slug']));
        }
        if (!empty($criteria['tour_type_slug'])) {
            $query->whereHas('tourType', fn ($q) => $q->where('slug', $criteria['tour_type_slug']));
        }
        if (!empty($criteria['category_slug'])) {
            $query->whereHas('categoryRelation', fn ($q) => $q->where('slug', $criteria['category_slug']));
        }
        if (!empty($criteria['duration_min']) || !empty($criteria['duration_max'])) {
            $min = $criteria['duration_min'] ?? 1;
            $max = $criteria['duration_max'] ?? 99;
            $query->whereRaw('CAST(duration AS UNSIGNED) BETWEEN ? AND ?', [$min, $max]);
        }

        return $query->latest()->limit($limit)->get();
    }
}
