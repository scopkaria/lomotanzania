<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mapbox' => [
        'token' => env('MAPBOX_TOKEN'),
    ],

    'exchange_rates' => [
        'url' => env('EXCHANGE_RATE_API_URL', 'https://open.er-api.com/v6/latest'),
    ],

    'safari' => [
        'whatsapp_number' => env('SAFARI_WHATSAPP_NUMBER'),
        'call_url'        => env('SAFARI_CALL_URL'),
    ],

    'google_cse' => [
        'api_key' => env('GOOGLE_CSE_API_KEY'),
        'cx'      => env('GOOGLE_CSE_CX'),
    ],

];
