<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafariPackage extends Model
{
    use HasSeoMeta, Translatable;

    protected array $translatable = ['title', 'short_description', 'description', 'highlights', 'overview_title', 'highlights_title', 'highlights_intro', 'inclusions_title', 'inclusions_intro'];

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'overview_title',
        'highlights',
        'included',
        'excluded',
        'highlights_title',
        'highlights_intro',
        'inclusions_title',
        'inclusions_intro',
        'duration',
        'tour_type',
        'category',
        'difficulty',
        'tour_type_id',
        'category_id',
        'price',
        'seasonal_pricing',
        'currency',
        'featured_image',
        'video_url',
        'map_embed',
        'status',
        'safari_type',
        'featured',
        'featured_order',
        'featured_label',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'title_translations',
        'short_description_translations',
        'description_translations',
        'highlights_translations',
        'overview_title_translations',
        'highlights_title_translations',
        'highlights_intro_translations',
        'inclusions_title_translations',
        'inclusions_intro_translations',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'featured' => 'boolean',
            'highlights' => 'array',
            'included' => 'array',
            'excluded' => 'array',
            'seasonal_pricing' => 'array',
            'title_translations' => 'array',
            'short_description_translations' => 'array',
            'description_translations' => 'array',
            'highlights_translations' => 'array',
            'overview_title_translations' => 'array',
            'highlights_title_translations' => 'array',
            'highlights_intro_translations' => 'array',
            'inclusions_title_translations' => 'array',
            'inclusions_intro_translations' => 'array',
        ];
    }

    public function getFullDescriptionAttribute(): ?string
    {
        $description = trim((string) $this->description);

        if ($description === '') {
            return null;
        }

        $decoded = $description;

        for ($attempt = 0; $attempt < 2; $attempt++) {
            $next = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($next === $decoded) {
                break;
            }

            $decoded = $next;
        }

        return $decoded;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function tourType(): BelongsTo
    {
        return $this->belongsTo(TourType::class);
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(SafariImage::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class);
    }
}
