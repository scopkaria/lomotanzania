<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariPlan extends Model
{
    protected $fillable = [
        'safari_package_id',
        'destinations',
        'months',
        'travel_group',
        'interests',
        'budget_range',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone',
        'contact_methods',
        'wants_updates',
        'know_destination',
        'travel_start_date',
        'travel_end_date',
    ];

    protected function casts(): array
    {
        return [
            'destinations' => 'array',
            'months' => 'array',
            'interests' => 'array',
            'contact_methods' => 'array',
            'wants_updates' => 'boolean',
            'travel_start_date' => 'date',
            'travel_end_date' => 'date',
        ];
    }

    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }
}
