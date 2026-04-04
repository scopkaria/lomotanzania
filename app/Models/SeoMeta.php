<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'seoable_type', 'seoable_id', 'locale',
        'focus_keyword', 'secondary_keywords', 'slug_preview',
        'seo_score', 'readability_score',
        'analysis_data', 'last_analyzed_at',
    ];

    protected $casts = [
        'analysis_data'    => 'array',
        'last_analyzed_at' => 'datetime',
        'seo_score'        => 'integer',
        'readability_score' => 'integer',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Score color class: red (0-40), yellow (41-70), green (71-100).
     */
    public function scoreColor(): string
    {
        if ($this->seo_score >= 71) return 'green';
        if ($this->seo_score >= 41) return 'yellow';
        return 'red';
    }

    public function readabilityColor(): string
    {
        if ($this->readability_score >= 71) return 'green';
        if ($this->readability_score >= 41) return 'yellow';
        return 'red';
    }
}
