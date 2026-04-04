<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerSetting extends Model
{
    protected $fillable = [
        'step_key',
        'title',
        'description',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public static function forStep(string $key): ?self
    {
        return static::where('step_key', $key)->first();
    }

    public static function allKeyed(): array
    {
        return static::all()->keyBy('step_key')->toArray();
    }
}
