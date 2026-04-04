<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SafariRequest extends Model
{
    protected $fillable = [
        'agent_id',
        'client_name',
        'client_email',
        'client_phone',
        'country',
        'travel_date',
        'people',
        'destinations',
        'activities',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'travel_date'  => 'date',
            'destinations' => 'array',
            'activities'   => 'array',
            'people'       => 'integer',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function response(): HasOne
    {
        return $this->hasOne(RequestResponse::class, 'request_id');
    }
}
