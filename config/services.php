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

        'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    // ... config lainnya
    
    'openweather' => [
        'key' => env('12328df0f14d68cdd7b6f22de027446d'),
        'base_url' => 'https://api.openweathermap.org/data/2.5',
    ],
    
    'stormglass' => [
        'key' => env('19d214d8-d70d-11f0-a148-0242ac130003-19d21546-d70d-11f0-a148-0242ac130003
'),
        'base_url' => 'https://api.stormglass.io/v2',
    ],
];

// OPENWEATHER_API_KEY=12328df0f14d68cdd7b6f22de027446d
// Stormglass_API_KEY=19d214d8-d70d-11f0-a148-0242ac130003-19d21546-d70d-11f0-a148-0242ac130003

