<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSeoMeta
{
    public function seoMetas(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    /**
     * Get SEO meta for a specific locale.
     */
    public function seoMeta(string $locale = 'en'): ?SeoMeta
    {
        return $this->seoMetas()->where('locale', $locale)->first();
    }

    /**
     * Get or create SEO meta for a given locale.
     */
    public function seoMetaOrNew(string $locale = 'en'): SeoMeta
    {
        return $this->seoMetas()->firstOrCreate(
            ['locale' => $locale],
            ['seo_score' => 0, 'readability_score' => 0]
        );
    }

    /**
     * Get the average SEO score across all locales.
     */
    public function averageSeoScore(): float
    {
        return round($this->seoMetas()->avg('seo_score') ?? 0);
    }
}
