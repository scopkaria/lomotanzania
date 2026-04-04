<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestResponse extends Model
{
    protected $fillable = [
        'request_id',
        'safari_title',
        'description',
        'price',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(SafariRequest::class, 'request_id');
    }
}
