<?php
switch (env('APP_ENV')) {
    case 'local':
    case 'development':
        $pusher_app_id = 1926808;
        $pusher_app_key = '0ee4b90b400628d0331d';
        $pusher_app_secret = '6e3577a87f4ec34b97ff';
        $pusher_app_cluster = 'ap2';

        break;

    case 'staging':
        $pusher_app_id = 1926809;
        $pusher_app_key = 'a5dee527655617511cab';
        $pusher_app_secret = '767124ac1947a6374245';
        $pusher_app_cluster = 'ap2';
        break;

    case 'production':
        $pusher_app_id_2 = 1926810;
        $pusher_app_key_2 = 'ca4a9d174db56bc7fd87';
        $pusher_app_secret_2 = '463171c2c1221a92d435';
        $pusher_app_cluster_2 = 'ap2';
 break;

};

switch (env('B2B_DEV')) {
    case 'local':
    case 'development':
        $pusher_app_id_b2b = 1960430;
        $pusher_app_key_b2b = 'a80894fc40705b88a39d';
        $pusher_app_secret_b2b = 'a6148737cfc962b74ad9';
        $pusher_app_cluster_b2b = 'ap2';
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

        'b2c_pusher' => [
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

        'b2b_pusher' => [
            'driver' => 'pusher',
            'key' => $pusher_app_key_b2b,
            'secret' => $pusher_app_secret_b2b,
            'app_id' => $pusher_app_id_b2b,
            'options' => [
                'cluster' =>$pusher_app_cluster_b2b,
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
