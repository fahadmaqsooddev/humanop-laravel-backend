<?php

if (env('APP_ENV') == 'local') {
    return [
        'client_dashboard_url' => 'http://127.0.0.1:8000',
        'b2b_dashboard_url' => 'http://127.0.0.1:8000',

    ];
} elseif (env('APP_ENV') == 'development') {
    return [
        'client_dashboard_url' => 'https://dev.humanop.com',
        'b2b_dashboard_url' => 'https://maestro-dev.humanop.com',
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'client_dashboard_url' => 'https://staging.humanop.com',
        'b2b_dashboard_url' => 'https://maestro-staging.humanop.com',
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'client_dashboard_url' => 'https://beta.humanop.com',
        'b2b_dashboard_url' => 'https://maestro.humanop.com',
    ];
}

