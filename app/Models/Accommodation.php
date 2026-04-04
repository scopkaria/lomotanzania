<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accommodation extends Model
{
    use HasSeoMeta, Translatable;

    protected array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'slug', 'description', 'category',
        'country_id', 'destination_id',
        'name_translations', 'description_translations',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
    ];

    protected function casts(): array
    {
        return [
            'name_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AccommodationImage::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }
}