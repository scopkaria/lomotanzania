<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'agent_id',
        'safari_package_id',
        'client_name',
        'client_email',
        'client_phone',
        'travel_date',
        'num_people',
        'total_price',
        'commission_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'travel_date'       => 'date',
            'total_price'       => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'num_people'        => 'integer',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function safari(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class, 'safari_package_id');
    }
}
