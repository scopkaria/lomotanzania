<?php

namespace App\Traits;

/**
 * Provides SEO data arrays for views.
 * Controllers use: return view('...', [..., ...$this->seoData($model, $title, $desc)])
 */
trait HasSeoData
{
    protected function seoData(?object $model = null, ?string $fallbackTitle = null, ?string $fallbackDescription = null, ?string $fallbackImage = null): array
    {
        $siteName = optional(\App\Models\Setting::first())->site_name ?? 'Lomo Tanzania Safari';

        $title = null;
        $description = null;
        $keywords = null;
        $ogImage = null;

        if ($model) {
            $title = $model->meta_title ?? null;
            $description = $model->meta_description ?? null;
            $keywords = $model->meta_keywords ?? null;

            // OG image cascade: og_image → featured_image → fallbackImage
            if (!empty($model->og_image)) {
                $ogImage = asset('storage/' . $model->og_image);
            } elseif (!empty($model->featured_image)) {
                $ogImage = asset('storage/' . $model->featured_image);
            }
        }

        return [
            'seoTitle' => $title ?: (($fallbackTitle ? $fallbackTitle . ' | ' : '') . $siteName),
            'seoDescription' => $description ?: $fallbackDescription,
            'seoKeywords' => $keywords,
            'seoOgImage' => $ogImage ?: ($fallbackImage ? asset('storage/' . $fallbackImage) : null),
        ];
    }
}
