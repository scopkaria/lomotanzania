<?php

namespace App\Traits;

use App\Models\Destination;
use App\Models\HeroSetting;
use App\Models\Post;
use App\Models\SafariPackage;
use App\Models\Testimonial;
use App\Models\TripadvisorReview;

trait LoadsSectionData
{
    protected function loadSectionData($section): array
    {
        $data = $section->data ?? [];

        return match ($section->section_type) {
            'hero' => [
                'heroSafaris' => (function () {
                    $settings = HeroSetting::instance();
                    $selectedIds = $settings->hero_safari_ids ?? [];

                    if (!empty($selectedIds)) {
                        return SafariPackage::where('status', 'published')
                            ->whereIn('id', $selectedIds)
                            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $selectedIds)) . ')')
                            ->get();
                    }

                    return SafariPackage::where('status', 'published')
                        ->where('featured', true)
                        ->orderBy('featured_order')
                        ->get();
                })(),
                'heroSettings' => HeroSetting::instance(),
            ],
            'split_hero', 'highlight', 'two_column_feature', 'experience_grid' => [],
            'featured_safaris', 'safari_grid', 'safari_list' => [
                'safaris' => SafariPackage::where('status', 'published')
                    ->when(
                        !empty($data['featured_only']) && $data['featured_only'] !== '0',
                        fn ($q) => $q->where('featured', true)
                    )
                    ->latest()
                    ->limit((int) ($data['count'] ?? 6))
                    ->get(),
            ],
            'destinations', 'destination_showcase' => [
                'destinations' => Destination::whereNotNull('featured_image')
                    ->with('country')
                    ->limit((int) ($data['count'] ?? 8))
                    ->get(),
            ],
            'testimonials', 'testimonial_slider' => [
                'testimonials' => Testimonial::approved()
                    ->with('safariPackage')
                    ->latest()
                    ->limit((int) ($data['count'] ?? 3))
                    ->get(),
            ],
            'blog' => [
                'posts' => Post::where('status', 'published')
                    ->latest('published_at')
                    ->limit((int) ($data['count'] ?? 3))
                    ->get(),
            ],
            'tripadvisor_reviews' => [
                'tripadvisorReviews' => TripadvisorReview::published()
                    ->orderByDesc('display_order')
                    ->orderByDesc('rating')
                    ->limit((int) ($data['count'] ?? 10))
                    ->get(),
            ],
            default => [],
        };
    }
}
