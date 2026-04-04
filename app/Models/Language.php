<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    protected $fillable = [
        'name', 'code', 'native_name', 'flag',
        'is_default', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('languages.active'));
        static::deleted(fn () => Cache::forget('languages.active'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get all active language codes as a flat array (cached).
     */
    public static function activeCodes(): array
    {
        return Cache::remember('languages.active', 3600, function () {
            return static::active()->pluck('code')->toArray();
        });
    }

    /**
     * Get the default language code.
     */
    public static function defaultCode(): string
    {
        return static::where('is_default', true)->value('code') ?? 'en';
    }
}
