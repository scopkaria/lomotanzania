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
     * Save focus_keyword to seo_meta table.
     */
    public function saveSeoMeta(array $data, string $locale = 'en'): void
    {
        $focusKeyword = $data['focus_keyword'] ?? null;

        // Only create/update if there's something to save
        if ($focusKeyword !== null) {
            $this->seoMetas()->updateOrCreate(
                ['locale' => $locale],
                ['focus_keyword' => $focusKeyword ?: null]
            );
        }
    }

    /**
     * Get the average SEO score across all locales.
     */
    public function averageSeoScore(): float
    {
        return round($this->seoMetas()->avg('seo_score') ?? 0);
    }
}
