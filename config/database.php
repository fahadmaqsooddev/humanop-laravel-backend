<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],
    'models' => [
        'Answer' => [
            'table' => 'answers',
            'fillable' => ['answer','sort','image','user_id','question_id', 'answer_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'AnswerCode' => [
            'table' => 'answer_codes',
            'fillable' => ['code','number','answer_id','code_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'BillingInfo' => [
            'table' => 'billing_infos',
            'fillable' => ['first_name','last_name','email','zip_code','address','user_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'CodeDetail' => [
            'table' => 'code_details',
            'fillable' => ['name','code','public_name','number','type','text'],
            'hidden' => ['created_at','updated_at']
        ],
        'GeneralSetting' => [
            'table' => 'general_settings',
            'fillable' => ['sidebar_color','text_color','background_color','navbar_color','user_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'Plan' => [
            'table' => 'plans',
            'fillable' => ['plan_id','name','billing_method','price','currency'],
            'hidden' => ['created_at','updated_at']
        ],
        'Question' => [
            'table' => 'questions',
            'fillable' => ['question','sort','active','gender', 'question_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'StripeSetting' => [
            'table' => 'stripe_settings',
            'fillable' => ['api_key','public_key','account_name','account_email', 'amount'],
            'hidden' => ['created_at','updated_at']
        ],
        'Subscription' => [
            'table' => 'subscriptions',
            'fillable' => ['stripe_id','stripe_status','stripe_price','quantity','trial_end_at','user_id','plan_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'User' => [
            'table' => 'users',
            'fillable' => ['first_name','last_name','email','password','phone','age_min','age_max','gender','signup_date','last_login','status','stripe_id','is_admin'],
            'hidden' => ['created_at','updated_at','remember_token','password']
        ],
        'Page' => [
            'table' => 'pages',
            'fillable' => ['name','title','meta_key','meta_description','text'],
            'hidden' => ['created_at','updated_at']
        ],
        'Slide' => [
            'table' => 'slides',
            'fillable' => ['heading','body','slide_id','sub','sub1', 'sub2'],
            'hidden' => ['created_at','updated_at']
        ],
        'SlideMedia' => [
            'table' => 'slide_media',
            'fillable' => ['image', 'slide_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'Assessment' => [
            'table' => 'assessments',
            'fillable' => ['user_id', 'page','sa','ma','jo','lu','ven','mer','so','de','dom','fe','gre','lun','nai','ne','pow','sp','tra','van','wil','g','s','c','em','ins','int','mov'],
            'hidden' => ['created_at','updated_at']
        ],
        'AssessmentDetail' => [
            'table' => 'assessment_details',
            'fillable' => ['user_id','assessment_id','question','answer'],
            'hidden' => ['created_at','updated_at']
        ],
        'DailyTip' => [
            'table' => 'daily_tips',
            'fillable' => ['title', 'description','code'],
            'hidden' => ['created_at','updated_at']
        ],
        'TipRecord' => [
            'table' => 'tip_records',
            'fillable' => ['user_id', 'tip_id'],
            'hidden' => ['created_at','updated_at']
        ],
        'Coupon' => [
            'table' => 'coupon',
            'fillable' => ['discount', 'limit', 'coupon'],
            'hidden' => ['created_at','updated_at']
        ],
    ]
];
