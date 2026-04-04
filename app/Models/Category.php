<?php

namespace App\Models;

use App\Traits\HasSeoMeta;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSeoMeta, Translatable;

    protected array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'slug', 'description', 'featured_image',
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

    public function safariPackages(): HasMany
    {
        return $this->hasMany(SafariPackage::class);
    }
}
