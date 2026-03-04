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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'blue' => [
        'webhook_secret' => env('WEBHOOK_SECRET'),
    ],

    'gohighlevel' => [
        'token' => env('GOHIGHLEVEL_API_TOKEN'),
        'location_id' => env('GOHIGHLEVEL_LOCATION_ID'),
        'base_uri' => env('GOHIGHLEVEL_BASE_URI', 'https://services.leadconnectorhq.com/'),
    ],

    'ip_api' => [
        'base_url' => env('IP_API_URL', 'http://ip-api.com/json/')
    ],

    'ipify' => [
        'base_url' => env('IPIFY_API_URL', 'https://api.ipify.org?format=text'),
    ],

    'oneSignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'auth_key' => env('ONESIGNAL_AUTH_KEY'),
    ],

    'fcm' => [
        'api_key' => env('FCM_API_KEY'),
        'server_key' => env('FCM_SERVER_KEY'),
    ]
];
