<?php

use Illuminate\Support\Str;

if (env('APP_ENV') == 'local') {
    return [
        'hai_api_urls' => 'http://3.85.39.145:8000/',
    ];
} elseif (env('APP_ENV') == 'development') {
    return [
        'hai_api_urls' => 'http://3.85.39.145:8000/',
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'hai_api_urls' => 'http://34.224.98.4:8000/',
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'hai_api_urls' => 'http://54.221.73.84:8000/',
    ];
}



//return [
//     'api' => 'http://54.227.7.149:8000/llm-response',
//    'dev_api_urls' => 'http://54.227.7.149:8000/',
//    'dev_old_api_urls' => 'http://3.87.21.19:8000/',
////    'dev_new_api_urls' => 'http://18.206.155.155:8000/',
//    'dev_new_api_urls' => 'http://3.85.39.145:8000/',
//    'staging_api_urls' => 'http://44.201.128.253:8000/',
//    'live_api_urls' => 'http://18.207.0.192:8000/',
//    'new_staging_api_urls' => 'http://34.224.98.4:8000/',
//    'hai_one_credit_token_count' => 1000,
//];
