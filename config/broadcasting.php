<?php
switch (env('APP_ENV')) {
    case 'local':
    case 'development':
        $pusher_app_id = 2014415;
        $pusher_app_key = '60962ff7feb624c41e8f';
        $pusher_app_secret = '4b33b23ad33470347062';
        $pusher_app_cluster = 'ap2';

        break;

    case 'staging':
        $pusher_app_id = 2014416;
        $pusher_app_key = 'f6f99e849dc96da462d5';
        $pusher_app_secret = '059b2b9bf0ec3a52908a';
        $pusher_app_cluster = 'ap2';
        break;

    case 'production':
        $pusher_app_id = 2014417;
        $pusher_app_key = '91b9c4ca2a229e3c2acb';
        $pusher_app_secret = 'e4783eebc7fa540de360';
        $pusher_app_cluster = 'ap2';
 break;

};

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => $pusher_app_key,
            'secret' => $pusher_app_secret,
            'app_id' => $pusher_app_id,
            'options' => [
                'cluster' =>$pusher_app_cluster ,
                'useTLS' => true,
//                'useTLS' => env('PUSHER_USE_TLS'),
//                'host' => env('PUSHER_LOCALHOST_IP'),
//                'port' => env('PUSHER_PORT'),
//                'scheme' => env('PUSHER_SCHEME')

            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
