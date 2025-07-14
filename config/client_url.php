<?php

if (env('APP_ENV') == 'local') {
    return [
        'client_dashboard_url' => 'http://127.0.0.1:8000',
        'admin_dashboard_url' => 'http://127.0.0.1:8000',
        'hai_admin_dashboard_url' => 'http://127.0.0.1:8000',
        'b2b_dashboard_url' => 'http://127.0.0.1:8000',
        'b2b_admin_dashboard_url' => 'http://127.0.0.1:8000',

    ];
} elseif (env('APP_ENV') == 'development') {
    return [
        'client_dashboard_url' => 'https://dev.humanop.com',
        'admin_dashboard_url' => 'https://dev.humanoptech.com',
        'hai_admin_dashboard_url' => 'https://dev-hai.humanoptech.com',
        'b2b_dashboard_url' => 'https://maestro-dev.humanop.com',
        'b2b_admin_dashboard_url' => 'https://maestro-dev.humanoptech.com',
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'client_dashboard_url' => 'https://staging.humanop.com',
        'admin_dashboard_url' => 'https://staging.humanoptech.com',
        'hai_admin_dashboard_url' => 'https://staging-hai.humanoptech.com',
        'b2b_dashboard_url' => 'https://maestro-staging.humanop.com',
        'b2b_admin_dashboard_url' => 'https://maestro-staging.humanoptech.com',
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'client_dashboard_url' => 'https://beta.humanop.com',
        'admin_dashboard_url' => 'https://beta.humanoptech.com',
        'hai_admin_dashboard_url' => 'https://beta-hai.humanoptech.com',
        'b2b_dashboard_url' => 'https://maestro.humanop.com',
        'b2b-admin_dashboard_url' => 'https://maestro.humanoptech.com',
    ];
}

