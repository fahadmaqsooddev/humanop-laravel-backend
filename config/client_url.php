<?php

if (env('APP_ENV') == 'local') {
    return [
        'client_dashboard_url' => 'http://127.0.0.1:8000',
        'b2b_dashboard_url' => 'http://127.0.0.1:8000',

    ];
} elseif (env('APP_ENV') == 'development') {
    return [
        'client_dashboard_url' => 'https://human-op-dev.vercel.app',
        'b2b_dashboard_url' => 'https://human-op-b2b.vercel.app',
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'client_dashboard_url' => 'https://human-op-staging.vercel.app',
        'b2b_dashboard_url' => 'https://human-op-b2b.vercel.app',
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'client_dashboard_url' => 'https://beta.humanop.com',
        'b2b_dashboard_url' => 'https://b2b-fc5n.vercel.app',
    ];
}

