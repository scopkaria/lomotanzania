<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripadvisorReview extends Model
{
    protected $fillable = [
        'tripadvisor_id',
        'reviewer_name',
        'reviewer_location',
        'reviewer_avatar',
        'title',
        'review_text',
        'rating',
        'review_date',
        'trip_type',
        'published',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'published'   => 'boolean',
            'review_date' => 'date',
            'rating'      => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
