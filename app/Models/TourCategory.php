<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// ADDED: Tour category model for Safari/Trekking/Beach classification
class TourCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'featured_image',
        'display_order',
    ];

    public function safariPackages(): BelongsToMany
    {
        return $this->belongsToMany(SafariPackage::class, 'safari_package_tour_category');
    }

    public function publishedSafaris(): BelongsToMany
    {
        return $this->safariPackages()->where('status', 'published');
    }
}
