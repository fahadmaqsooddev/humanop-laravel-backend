<?php

if (env('APP_ENV') == 'local') {
    return [
        'client_dashboard_url' => 'http://127.0.0.1:8000'
    ];
} elseif (env('APP_ENV') == 'development') {
    return [
        'client_dashboard_url' => 'https://human-opi-3dgc.vercel.app'
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'client_dashboard_url' => 'https://human-opi.vercel.app'
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'client_dashboard_url' => 'https://human-op-beta.vercel.app',
    ];
}

