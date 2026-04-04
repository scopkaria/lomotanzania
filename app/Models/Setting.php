<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'logo_path',
        'meta_description',
        'default_og_image',
        'google_analytics_id',
        'google_search_console',
        'notification_email',
        'notify_inquiry',
        'notify_safari_request',
        'notify_safari_plan',
    ];

    protected $casts = [
        'notify_inquiry'        => 'boolean',
        'notify_safari_request' => 'boolean',
        'notify_safari_plan'    => 'boolean',
    ];
}
