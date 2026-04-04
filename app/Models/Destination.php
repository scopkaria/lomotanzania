<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasSeoMeta, Translatable;

    protected array $translatable = ['name', 'description'];

    protected $fillable = [
        'country_id',
        'name',
        'slug',
        'description',
        'featured_image',
        'latitude',
        'longitude',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'name_translations',
        'description_translations',
    ];

    protected $casts = [
        'name_translations' => 'array',
        'description_translations' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function safariPackages(): BelongsToMany
    {
        return $this->belongsToMany(SafariPackage::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class);
    }
}
