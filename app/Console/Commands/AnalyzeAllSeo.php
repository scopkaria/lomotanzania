<?php

namespace App\Console\Commands;

use App\Models\SeoMeta;
use App\Services\SeoAnalyzer;
use Illuminate\Console\Command;

/**
 * Batch analyze all content models and update SEO scores.
 * Schedule weekly or run manually: php artisan seo:analyze-all
 */
class AnalyzeAllSeo extends Command
{
    protected $signature = 'seo:analyze-all {--model= : Analyze a specific model type only}';
    protected $description = 'Analyze SEO for all content models and update scores';

    protected array $models = [
        'safari'        => \App\Models\SafariPackage::class,
        'destination'   => \App\Models\Destination::class,
        'country'       => \App\Models\Country::class,
        'page'          => \App\Models\Page::class,
        'post'          => \App\Models\Post::class,
        'tour_type'     => \App\Models\TourType::class,
        'category'      => \App\Models\Category::class,
        'accommodation' => \App\Models\Accommodation::class,
    ];

    public function handle(SeoAnalyzer $analyzer): int
    {
        $models = $this->models;

        if ($filter = $this->option('model')) {
            $models = array_filter($models, fn($k) => $k === $filter, ARRAY_FILTER_USE_KEY);
        }

        $total = 0;

        foreach ($models as $label => $modelClass) {
            $this->info("Analyzing {$label}...");

            $modelClass::query()->each(function ($item) use ($analyzer, &$total) {
                $data = $this->extractData($item);
                $result = $analyzer->analyze($data);

                $seo = $item->seoMetas()->firstOrNew(['locale' => 'en']);
                $seo->fill([
                    'seo_score'         => $result['seo_score'],
                    'readability_score' => $result['readability_score'],
                    'analysis_data'     => $result,
                    'last_analyzed_at'  => now(),
                    'focus_keyword'     => $seo->focus_keyword, // preserve existing
                ]);
                $seo->save();

                $total++;
                $this->line("  [{$item->id}] Score: {$result['seo_score']}/100");
            });
        }

        $this->info("Analyzed {$total} items.");
        return self::SUCCESS;
    }

    protected function extractData($model): array
    {
        // Get title
        $title = $model->meta_title ?? '';
        if (!$title) {
            if (is_array($model->title ?? null)) {
                $title = $model->title['en'] ?? ($model->title[0] ?? '');
            } else {
                $title = $model->title ?? $model->name ?? '';
            }
        }

        // Get content/description
        $content = '';
        if (is_array($model->content ?? null)) {
            $content = $model->content['en'] ?? '';
        } elseif (!empty($model->description)) {
            $content = $model->description;
        }

        // For SafariPackage, combine description fields
        if ($model instanceof \App\Models\SafariPackage) {
            $content = ($model->short_description ?? '') . ' ' . ($model->description ?? '');
        }

        $seo = $model->seoMetas ? $model->seoMetas()->where('locale', 'en')->first() : null;

        return [
            'title'            => $title,
            'meta_title'       => $model->meta_title ?? '',
            'meta_description' => $model->meta_description ?? '',
            'content'          => $content,
            'slug'             => $model->slug ?? '',
            'focus_keyword'    => $seo->focus_keyword ?? '',
        ];
    }
}
