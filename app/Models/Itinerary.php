<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    use Translatable;

    protected array $translatable = ['title', 'description'];

    protected $fillable = [
        'safari_package_id',
        'destination_id',
        'accommodation_id',
        'day_number',
        'title',
        'description',
        'image_path',
        'title_translations',
        'description_translations',
    ];

    protected function casts(): array
    {
        return [
            'title_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function accommodationRelation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
