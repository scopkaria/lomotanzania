<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'logo_width',
        'header_color',
        'meta_description',
        'default_og_image',
        'google_analytics_id',
        'google_search_console',
        'bing_webmaster_code',
        'yandex_verification_code',
        'baidu_verification_code',
        'notification_email',
        'notify_inquiry',
        'notify_safari_request',
        'notify_safari_plan',
        'whatsapp_number',
        'tripadvisor_url',
        'phone_number',
        'chat_greeting',
        'chat_enabled',
    ];

    protected $casts = [
        'logo_width'            => 'integer',
        'notify_inquiry'        => 'boolean',
        'notify_safari_request' => 'boolean',
        'notify_safari_plan'    => 'boolean',
        'chat_enabled'          => 'boolean',
    ];
}
