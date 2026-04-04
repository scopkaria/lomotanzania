<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    protected $casts = [
        'name' => 'array',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function translatedName(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        $names  = $this->name ?? [];

        return $names[$locale] ?? $names['en'] ?? '';
    }
}
