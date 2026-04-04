<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class Post extends Model
{
    use HasSeoMeta;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'blog_category_id',
        'author_id',
        'meta',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'title'        => 'array',
        'content'      => 'array',
        'excerpt'      => 'array',
        'meta'         => 'array',
        'published_at' => 'datetime',
    ];

    // ── Relationships ──

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Translation helpers ──

    public function translatedTitle(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        $titles = $this->title ?? [];

        return $titles[$locale] ?? $titles['en'] ?? '';
    }

    public function translatedExcerpt(?string $locale = null): string
    {
        $locale  = $locale ?: App::getLocale();
        $excerpts = $this->excerpt ?? [];

        return $excerpts[$locale] ?? $excerpts['en'] ?? '';
    }

    public function translatedContent(?string $locale = null): string
    {
        $locale  = $locale ?: App::getLocale();
        $content = $this->content ?? [];

        return $content[$locale] ?? $content['en'] ?? '';
    }

    public function translatedMeta(string $key, ?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        $meta   = $this->meta ?? [];

        if (isset($meta[$key]) && is_array($meta[$key])) {
            return $meta[$key][$locale] ?? $meta[$key]['en'] ?? '';
        }

        return $meta[$key] ?? '';
    }

    // ── Scopes ──

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // ── Helpers ──

    public function readingTime(): int
    {
        $text = strip_tags($this->translatedContent());
        $words = str_word_count($text);

        return max(1, (int) ceil($words / 200));
    }
}
