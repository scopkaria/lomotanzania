<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Agent extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'country',
        'commission_rate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function safariRequests(): HasMany
    {
        return $this->hasMany(SafariRequest::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function totalEarnings(): float
    {
        return (float) $this->bookings()->whereIn('status', ['pending', 'confirmed'])->sum('commission_amount');
    }
}
