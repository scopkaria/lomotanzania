<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariImage extends Model
{
    protected $fillable = [
        'safari_package_id',
        'image_path',
    ];

    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }
}
