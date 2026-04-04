<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'safari_package_id',
        'inquiry_type',
        'name',
        'email',
        'phone',
        'country',
        'travel_date',
        'number_of_people',
        'message',
        'contact_methods',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'contact_methods' => 'array',
        ];
    }

    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }
}
