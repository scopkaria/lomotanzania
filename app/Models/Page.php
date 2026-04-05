<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Page extends Model
{
    use HasSeoMeta;
    protected $fillable = [
        'title',
        'slug',
        'type',
        'is_homepage',
        'content',
        'sections',
        'status',
        'template',
        'layout',
        'bg_color',
        'section_spacing',
        'sort_order',
        'meta',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    protected $casts = [
        'title'       => 'array',
        'content'     => 'array',
        'sections'    => 'array',
        'meta'        => 'array',
        'is_homepage' => 'boolean',
    ];

    /**
     * Get translated title.
     */
    public function translatedTitle(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        $titles = $this->title ?? [];

        return $titles[$locale] ?? $titles['en'] ?? '';
    }

    /**
     * Get translated content for a specific key.
     */
    public function translatedContent(string $key, ?string $locale = null): string
    {
        $locale  = $locale ?: App::getLocale();
        $content = $this->content ?? [];

        return $content[$key][$locale] ?? $content[$key]['en'] ?? '';
    }

    /**
     * Get sections with translated content.
     */
    public function translatedSections(?string $locale = null): array
    {
        $locale   = $locale ?: App::getLocale();
        $sections = $this->sections ?? [];

        return array_map(function ($section) use ($locale) {
            $translated = $section;
            // Translate fields that have locale keys
            foreach (['heading', 'subheading', 'body', 'button_text', 'html'] as $field) {
                if (isset($section[$field]) && is_array($section[$field])) {
                    $translated[$field] = $section[$field][$locale] ?? $section[$field]['en'] ?? '';
                }
            }
            return $translated;
        }, $sections);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    public function isSystemPage(): bool
    {
        return $this->is_homepage || $this->type === 'system';
    }

    public function liveUrl(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale() ?: 'en';

        return match ($this->slug) {
            'homepage'     => route('home', ['locale' => $locale]),
            'safaris'      => route('safaris.index', ['locale' => $locale]),
            'destinations' => route('destinations.index', ['locale' => $locale]),
            'experiences'  => route('experiences.index', ['locale' => $locale]),
            'blog'         => route('blog.index', ['locale' => $locale]),
            'contact'      => route('contact', ['locale' => $locale]),
            default        => route('page.show', ['locale' => $locale, 'slug' => $this->slug]),
        };
    }

    public function pageSections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function activeSections()
    {
        return $this->pageSections()->where('is_active', true);
    }
}
