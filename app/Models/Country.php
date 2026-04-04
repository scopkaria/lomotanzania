<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasSeoMeta;
    protected $fillable = [
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
    ];

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    public function safariPackages(): BelongsToMany
    {
        return $this->belongsToMany(SafariPackage::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class);
    }
}
