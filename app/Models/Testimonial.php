<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'safari_package_id',
        'name',
        'message',
        'rating',
        'approved',
    ];

    protected function casts(): array
    {
        return [
            'approved' => 'boolean',
        ];
    }

    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }
}
