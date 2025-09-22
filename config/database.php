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
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
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
            'fillable' => ['answer', 'sort', 'image', 'user_id', 'question_id', 'answer_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'AnswerCode' => [
            'table' => 'answer_codes',
            'fillable' => ['code', 'number', 'answer_id', 'code_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'BillingInfo' => [
            'table' => 'billing_infos',
            'fillable' => ['first_name', 'last_name', 'email', 'zip_code', 'address', 'user_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'CodeDetail' => [
            'table' => 'code_details',
            'fillable' => ['name', 'code', 'public_name', 'number', 'type', 'text'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'GeneralSetting' => [
            'table' => 'general_settings',
            'fillable' => ['sidebar_color', 'text_color', 'background_color', 'navbar_color', 'user_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Question' => [
            'table' => 'questions',
            'fillable' => ['question', 'sort', 'active', 'multiple', 'gender', 'question_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'StripeSetting' => [
            'table' => 'stripe_settings',
            'fillable' => ['api_key', 'public_key', 'account_name', 'account_email', 'amount', 'type'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Subscription' => [
            'table' => 'subscriptions',
            'fillable' => ['stripe_id', 'stripe_status', 'stripe_price', 'quantity', 'trial_end_at', 'user_id', 'plan_id', 'name'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'User' => [
            'table' => 'users',
            'fillable' => ['parent_referal_plan_name','last_updated_daily_tip','first_name', 'last_name', 'email', 'password', 'phone', 'date_of_birth', 'gender', 'signup_date', 'last_login', 'status', 'stripe_id', 'is_admin', 'payment_method', 'pm_type', 'pm_last_four', 'pm_exp_month', 'pm_exp_year', 'google_id', 'is_feedback', 'password_set', 'is_permanently_deleted', 'image_id', 'apple_id', 'hai_chat', 'referral_code', 'referred_by', 'practitioner_id', 'timezone', 'two_way_auth', 'intro_check', 'reset_password', 'app_intro_check', 'reset_password_token', 'email_verify_token', 'step', 'email_verified_at', 'register_from_app', 'device_token', 'company_name', 'business_sub_stratergy_id', 'business_id', 'work_email', 'b2b_step', 'team_department', 'prompt_notification', 'version_update', 'sms_verify_code', 'phone_verified_at', 'complete_assessment_walkthrough', 'complete_tutorial', 'chat_summary', 'profile_status', 'hai_status', 'credits_log', 'life_alchemist', 'excited_connect', 'note', 'profile_privacy', 'hai_privacy', 'registration_checkout', 'trial_day', 'trial_time', 'set_daily_tip_time','beta_breaker_club'],
            'hidden' => ['created_at', 'updated_at', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret']
        ],
        'Page' => [
            'table' => 'pages',
            'fillable' => ['name', 'title', 'meta_key', 'meta_description', 'text'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Slide' => [
            'table' => 'slides',
            'fillable' => ['heading', 'body', 'slide_id', 'sub', 'sub1', 'sub2'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'SlideMedia' => [
            'table' => 'slide_media',
            'fillable' => ['image', 'slide_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Assessment' => [
            'table' => 'assessments',
            'fillable' => ['user_id', 'page', 'sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so', 'de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil', 'g', 's', 'c', 'em', 'ins', 'int', 'mov', 'created_at', 'type', 'updated_at', 'reset_assessment', 'web_page', 'app_page', 'after_reset_assessment_updated_at'],
//            'hidden' => ['updated_at']
        ],
        'AssessmentDetail' => [
            'table' => 'assessment_details',
            'fillable' => ['user_id', 'assessment_id', 'question', 'answer'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'DailyTip' => [
            'table' => 'daily_tips',
            'fillable' => ['title', 'description', 'code', 'user_id', 'is_read', 'text', 'subscription_type', 'min_point', 'max_point', 'interval_of_life'],
            'hidden' => ['created_at', 'updated_at']
        ],

        'RoleTemplate' => [
            'table' => 'role_templates',
            'fillable' => ['code', 'min_point', 'max_point', 'role_name'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'TaskResponsibilities' => [
            'table' => 'task_responsibilities',
            'fillable' => ['role_template_id', 'tags'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'TipRecord' => [
            'table' => 'tip_records',
            'fillable' => ['user_id', 'tip_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Coupon' => [
            'table' => 'coupons',
            'fillable' => ['discount', 'limit', 'coupon', 'remaining_redemption'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'CouponRedemption' => [
            'table' => 'coupon_redemptions',
            'fillable' => ['user_id', 'coupon_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'AlchemyCode' => [
            'table' => 'alchemy_codes',
            'fillable' => ['number', 'code', 'image'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Payment' => [
            'table' => 'payments',
            'fillable' => ['user_id', 'coupon_id', 'discount_price', 'total_price', 'assessment_id', 'created_at'],
            'hidden' => ['updated_at']
        ],
        'Upload' => [

            'table' => 'uploads',
            'fillable' => ['name', 'path', 'extension', 'hash', 'pre_fill'],
            'hidden' => ['created_at', 'updated_at', 'deleted_at'],
        ],
        'Story' => [
            'table' => 'stories',
            'fillable' => ['user_id', 'upload_id', 'created_at', 'file_type'],
            'hidden' => ['updated_at', 'deleted_at']
        ],
        'StoryView' => [
            'table' => 'story_views',
            'fillable' => ['user_id', 'story_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Post' => [
            'table' => 'posts',
            'fillable' => ['description', 'upload_id', 'user_id', 'approve', 'post_id', 'created_at'],
            'hidden' => ['deleted_at', 'updated_at'],
        ],
        'PostLike' => [
            'table' => 'post_likes',
            'fillable' => ['post_id', 'user_id', 'post_comment_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'PostComment' => [
            'table' => 'post_comments',
            'fillable' => ['comment', 'post_id', 'user_id'],
            'hidden' => ['deleted_at', 'created_at', 'updated_at'],
        ],
        'AssessmentColorCode' => [
            'table' => 'assessment_color_code',
            'fillable' => ['assessment_id', 'code', 'code_color', 'code_number'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'Follow' => [
            'table' => 'follows',
            'fillable' => ['user_id', 'follow_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'MessageThread' => [
            'table' => 'message_threads',
            'fillable' => ['sender_id', 'receiver_id', 'updated_at'],
            'hidden' => ['deleted_at', 'created_at']
        ],
        'Message' => [
            'table' => 'messages',
            'fillable' => ['sender_id', 'message', 'upload_id', 'is_read', 'message_thread_id', 'created_at'],
            'hidden' => ['updated_at']
        ],
        'Feedback' => [
            'table' => 'feedbacks',
            'fillable' => ['comment', 'user_id', 'approve', 'rating', 'title', 'platform', 'image_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'Connection' => [
            'table' => 'connections',
            'fillable' => ['status', 'friend_id', 'user_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'Podcast' => [
            'table' => 'podcast',
            'fillable' => ['title', 'audio_id', 'thumbnail_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'HaiChat' => [
            'table' => 'haichat',
            'fillable' => ['user_id', 'query', 'answer', 'likedislike', 'admin_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'ClientQuery' => [
            'table' => 'client_query',
            'fillable' => ['user_id', 'query', 'response', 'chat_id', 'conversation_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'QueryAnswer' => [
            'table' => 'query_answer',
            'fillable' => ['query_id', 'answer', 'approved'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'Plan' => [
            'table' => 'plans',
            'fillable' => ['plan_id', 'name', 'billing_method', 'interval_count', 'price', 'currency', 'plan_type', 'no_of_team_members', 'status'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'Point' => [
            'table' => 'hai_points',
            'fillable' => ['user_id', 'point', 'is_b2b'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'PointLog' => [
            'table' => 'hai_point_logs',
            'fillable' => ['user_id', 'point', 'type', 'plan', 'is_added', 'is_b2b'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'LibraryResource' => [
            'table' => 'library_resources',
            'fillable' => ['heading', 'slug', 'upload_id', 'resource_category_id', 'description', 'content', 'source_id', 'source_url', 'embed_link', 'relevance','thumbnail_id'],
            'hidden' => ['updated_at'],
        ],
        'PermissionResource' => [
            'table' => 'permission_resources',
            'fillable' => ['resource_id', 'permission', 'point', 'price'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'ActionPlan' => [
            'table' => 'action_plans',
            'fillable' => ['plan_text', 'user_id', 'updated_at', 'text', 'assessment_id', 'priority', 'type'],
            'hidden' => ['created_at'],
        ],
        'ResourceCategory' => [
            'table' => 'resource_categories',
            'fillable' => ['name'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'IntentionPlan' => [
            'table' => 'intention_plan',
            'fillable' => ['user_id', 'intention_option_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'PdfGenerate' => [
            'table' => 'pdf_generates',
            'fillable' => ['user_id', 'assessment_id', 'code_detail_id', 'code_number'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'ModelHasRole' => [
            'table' => 'model_has_roles',
            'fillable' => ['role_id', 'model_type', 'model_id']
        ],
        'IntentionOption' => [
            'table' => 'intention_options',
            'fillable' => ['description'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'InformationIcon' => [
            'table' => 'information_icon',
            'fillable' => ['name', 'information'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'Emailtemplate' => [
            'table' => 'email_templates',
            'fillable' => ['name', 'format'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'UserDailyTip' => [
            'table' => 'user_daily_tips',
            'fillable' => ['user_id', 'daily_tip_id', 'is_read', 'assessment_id', 'favorite_tip'],
            'hidden' => ['updated_at'],
        ],
        'Version' => [
            'table' => 'version_control',
            'fillable' => ['version', 'note'],
            'hidden' => ['created_at', 'updated_at', 'deleted_at'],
        ],
        'OptimizationPlan' => [
            'table' => 'optimization_plan',
            'fillable' => ['priority', 'condition', 'content'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'Chatbot' => [
            'table' => 'chat_bots', //chatbot
            'fillable' => ['name', 'description', 'temperature', 'max_tokens', 'chunks', 'model_type', 'is_connected'], //'name','description','publish_path','is_published','brain_name'
            'hidden' => ['created_at', 'updated_at', 'deleted_at'], //'created_at','updated_at'
        ],
        'ChatPrompt' => [
            'table' => 'hai_chat_prompts', //hai_chat_prompts
            'fillable' => ['name', 'prompt', 'restriction', 'chat_bot_id', 'persona_name', 'human_op_app', 'maestro_app', 'is_training', 'maestro_app_id'], // 'name','prompt','restriction'
            'hidden' => ['created_at', 'updated_at'], //'created_at','updated_at'
        ],
        'HaiChatEmbedding' => [
            'table' => 'hai_chat_embeddings',
            'fillable' => ['name', 'request_id', 'created_at', 'updated_at', 'ready_for_training'],
            'hidden' => [],
        ],
        'HaiChatActiveEmbedding' => [
            'table' => 'hai_chat_active_embeddings',
            'fillable' => ['chat_bot', 'request_id', 'group_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'HaiChatSetting' => [
            'table' => 'hai_chat_setting',
            'fillable' => ['id', 'temperature', 'max_token', 'chunk', 'model_type', 'chat_bot_id', 'plan_id', 'persona_text', 'persona_name', 'human_op_app', 'maestro_app', 'maestro_app_id', 'is_training'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'HaiChatConversation' => [
            'table' => 'hai_chat_conversation',
            'fillable' => ['id', 'message', 'reply', 'user_id', 'is_liked', 'chat_bot_id'], //'id','chatbot','message','reply','user_id','is_liked'
            'hidden' => ['created_at', 'updated_at'],
        ],
        'EmbeddingSetting' => [
            'table' => 'embedding_setting',
            'fillable' => ['id', 'embedding', 'chunk'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'HaiChaiChunk' => [
            'table' => 'hai_chat_chunks',
            'fillable' => ['id', 'embedding', 'chatbot', 'query', 'retrieved_docs'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'UserInvite' => [
            'table' => 'user_invites',
            'fillable' => ['id', 'email', 'link', 'role', 'members_limit', 'total_member_limit', 'send_invite_time'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'UserInviteLog' => [
            'table' => 'user_invite_log',
            'fillable' => ['id', 'invite_id', 'role'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'EmbeddingGroup' => [
            'table' => 'embedding_groups',
            'fillable' => ['id', 'name', 'created_at', 'updated_at', 'description'],
            'hidden' => ['deleted_at'],
        ],
        'ChatbotKeyword' => [
            'table' => 'chatbot_keywords',
            'fillable' => ['word', 'chatbot_id', 'message'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'GroupEmbedding' => [
            'table' => 'group_embeddings',
            'fillable' => ['embedding_id', 'group_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'Notification' => [
            'table' => 'notifications',
            'fillable' => ['type', 'message', 'read', 'created_at', 'user_id', 'device_token', 'permission', 'notification_priority', 'role', 'sender_id'],
            'hidden' => ['updated_at', 'deleted_at'],
        ],
        'LlmModel' => [
            'table' => 'llm_models',
            'fillable' => ['model_name', 'model_value'],
            'hidden' => ['updated_at', 'deleted_at'],
        ],
        'BusinessStrategies' => [
            'table' => 'business_strategies',
            'fillable' => ['name'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'BusinessSubStrategies' => [
            'table' => 'business_sub_strategies',
            'fillable' => ['business_strategy_id', 'name'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'B2BTaskAndResponsibilities' => [
            'table' => 'b2b_tasks_responsibilities',
            'fillable' => ['role_template_id', 'name', 'tag1', 'tag2', 'tag3'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'UserOptimalTrait' => [
            'table' => 'user_optimal_trait',
            'fillable' => ['user_id', 'optimal_trait', 'status'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'FineTuneContent' => [
            'table' => 'fine_tune_content',
            'fillable' => ['question', 'answer', 'is_fine_tuned', 'queued_for_fine_tuning'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'B2BSupport' => [
            'table' => 'b2b_support',
            'fillable' => ['title', 'description', 'image_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'B2BBusinessCandidates' => [
            'table' => 'business_candidates',
            'fillable' => ['business_id', 'candidate_id', 'is_permanently_deleted', 'future_consideration', 'role', 'share_data', 'request_access', 'future_consideration_share_date', 'first_time_share_data', 'paid'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'B2BCandidateStat' => [
            'table' => 'b2b_candidate_stats',
            'fillable' => ['business_id', 'candidate_id', 'action_plan_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'AnalyticsModel' => [
            'table' => 'analytics',
            'fillable' => ['llm_model_id','prompt_token','completion_token','total_token','query'],
            'hidden' => ['updated_at','deleted_at'],
        ],
        'B2BNotes'=>[
            'table'=>'b2b_notes',
            'fillable'=>['business_id','user_id','note'],
            'hidden'=>['updated_at','created_at']
        ],
        'B2BIntentionOption' => [
            'table' => 'b2b_intention_option',
            'fillable' => ['intention_option'],
            'hidden' => ['updated_at', 'created_at']
        ],
        'SelectIntentionOption' => [
            'table' => 'select_b2b_intention',
            'fillable' => ['business_id', 'intention_option_id'],
            'hidden' => ['updated_at', 'created_at']
        ],
        'UserCandidateInvite' => [
            'table' => 'b2b_users_invites',
            'fillable' => ['company_id', 'invite_link_id', 'role'],
        ],
        'AssessmentWalkThrough' => [
            'table' => 'assessment_walkthrough',
            'fillable' => ['code_name', 'title', 'overview', 'optimal', 'optimization'],
            'hidden' => ['updated_at', 'created_at']
        ],
        'TrainingFile' => [
            'table' => 'training_files',
            'fillable' => ['name', 'file_name'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'PushNotification' => [
            'table' => 'push_notification',
            'fillable' => ['user_id', 'optimal_trait', 'daily_tip', 'reset_assessment', 'resource'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'VersionControlDescription' => [
            'table' => 'version_control_descriptions',
            'fillable' => ['version_id', 'description', 'platform', 'version_heading'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'SmsNotification' => [
            'table' => 'aws_credential',
            'fillable' => ['public_key', 'secret_key', 'region'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'SubscriptionItem' => [
            'table' => 'subscription_items',
            'fillable' => ['subscription_id', 'stripe_id', 'stripe_product', 'stripe_price', 'quantity'],
            'hidden' => ['created_at']
        ],
        'B2BCoupon' => [
            'table' => 'b2b_coupons',
            'fillable' => ['coupon_name', 'coupon_code', 'coupon_limit', 'coupon_duration'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'AssessmentIntro' => [
            'table' => 'assessment_intro',
            'fillable' => ['name', 'code', 'public_name', 'number', 'type', 'text', 'video', 'p_name'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'PublishedChatBot' => [
            'table' => 'published_chat_bot',
            'fillable' => ['name', 'description', 'max_tokens', 'temperature', 'chunks', 'model_type', 'persona_name', 'prompt', 'restriction', 'embedding_ids', 'restricted_keywords', 'chat_bot_id', 'is_connected'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'UserShareAssessment' => [
            'table' => 'user_share_assessment',
            'fillable' => ['user_id', 'interval_of_life', 'traits', 'motivational_driver', 'alchemic_boundaries', 'communication_style', 'perception_of_life', 'energy_pool'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'UserTagline' => [
            'table' => 'user_taglines',
            'fillable' => ['user_id', 'tagline'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'NetworkTutorial' => [
            'table' => 'network_tutorials',
            'fillable' => ['title', 'description', 'icon_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'LoginStreaks' => [
            'table' => 'login_streaks',
            'fillable' => ['user_id', 'login_days', 'complete_streaks'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'HumanOpPoints' => [
            'table' => 'humanop_points',
            'fillable' => ['user_id', 'points'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'VideoProgress' => [
            'table' => 'video_progress',
            'fillable' => ['assessment_id', 'video_name', 'video_progress','watch_time'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'GamificationBadgesAchievement' => [
            'table' => 'gamification_badges_achievement',
            'fillable' => ['user_id', 'badges'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'GamificationMedalRewards' => [
            'table' => 'gamification_medal_rewards',
            'fillable' => ['user_id', 'medals'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'GamificationPerformanceLevel' => [
            'table' => 'gamification_performance_level',
            'fillable' => ['user_id', 'performance', 'level'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'ShopCategory' => [
            'table' => 'humanop_shop_categories',
            'fillable' => ['name'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'ShopCategoryResource' => [
            'table' => 'humanop_shop_resources',
            'fillable' => ['heading', 'slug', 'humanop_shop_category_id', 'point', 'video_id', 'audio_id', 'document_id', 'price','image_id','description','thumbnail_id'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'HumanOpShopTraits' => [
            'table' => 'humanop_shop_traits',
            'fillable' => ['humanop_shop_resource_id', 'trait_name'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'FaqModel' => [
            'table' => 'faq_questions',
            'fillable' => ['question', 'answer'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'HumanOpLibraries' => [
            'table' => 'humanop_libraries',
            'fillable' => ['user_id', 'item_id', 'type', 'library_resource_id'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'CreditPlan' => [
            'table' => 'credit_plans',
            'fillable' => ['price', 'credits'],
            'hidden' => ['deleted_at', 'updated_at', 'created_at'],
        ],
        'HaiThread' => [
            'table' => 'hai_threads',
            'fillable' => ['title', 'is_b2b', 'user_id', 'hai_thread_id'],
            'hidden' => ['deleted_at', 'updated_at', 'created_at'],
        ],
        'Customization' => [
            'table' => 'customizations',
            'fillable' => ['points', 'detail'],
            'hidden' => ['updated_at', 'created_at'],
        ],
        'AnnouncementNews' => [
            'table' => 'announcement_news',
            'fillable' => ['title', 'description', 'updated_at'],
            'hidden' => ['created_at'],
        ],
        'RecentActivity' => [
            'table' => 'recent_activities',
            'fillable' => ['business_id', 'type', 'message', 'read'],
            'hidden' => ['created_at'],
        ],
        'MultiMediaStats' => [
            'table' => 'multi_media_stats',
            'fillable' => ['user_id', 'audio_id', 'time'],
            'hidden' => ['created_at'],
        ],
        'Playlist' => [
            'table' => 'playlist',
            'fillable' => ['user_id', 'audio_id', 'title', 'description','image_id'],
            'hidden' => ['created_at'],
        ],
        'PlaylistLog' => [
            'table' => 'playlist_log',
            'fillable' => ['playlist_id', 'resource_item_id', 'shop_item_id', 'user_id', 'podcast_id','order'],
            'hidden' => ['created_at'],
        ],
        'PlaylistItemTrack' => [
            'table' => 'playlist_item_track',
            'fillable' => ['playlist_id', 'user_id', 'item_id', 'playlist_time'],
            'hidden' => ['created_at'],
        ],
        'ResultVideo' => [
            'table' => 'assessment_result_videos',
            'fillable' => ['public_name', 'video', 'video_upload_id'],
            'hidden' => ['created_at'],
        ],
        'SuggestedItem' => [
            'table' => 'suggested_items',
            'fillable' => ['title', 'description', 'image_id', 'video_id', 'audio_id','module_type'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'HumanOpItemsGridActivitiesLog' => [
            'table' => 'humnop_items_grid_activities_log',
            'fillable' => ['resource_item_id', 'shop_item_id', 'suggested_item_id', 'grid_name'],
            'hidden' => ['created_at', 'updated_at'],
        ],
        'TeamDepartmentMembers' => [
            'table' => 'teams_or_departments_members',
            'fillable' => ['team_department_id', 'member_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'TeamDepartmentModel' => [
            'table' => 'teams_or_departments',
            'fillable' => ['name', 'description', 'location', 'type', 'member_id', 'team_id', 'business_id', 'logo_upload_id', 'parent_id', 'level'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'SuggestionForYou' => [
            'table' => 'suggestion_for_you',
            'fillable' => ['user_id', 'suggested_item_id'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'AssessmentVideoTrack' => [
            'table' => 'assessment_video_track',
            'fillable' => ['assessment_id', 'user_id', 'video_name', 'video_time'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'UserEmailPhoneNumber' => [
            'table' => 'user_email_phone_numbers',
            'fillable' => ['user_id', 'email', 'phone_no', 'default_email', 'default_phone_no'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'TraitCompatibilityReferenceKeys' => [
            'table' => 'trait_compatibility_reference_keys',
            'fillable' => ['first_reference_key', 'second_reference_key', 'volume'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'DriverCompatibilityReferenceKeys' => [
            'table' => 'driver_compatibility_reference_keys',
            'fillable' => ['first_reference_key', 'second_reference_key', 'volume'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'EnergyPoolCompatibilityReferenceKeys' => [
            'table' => 'energy_pool_compatibility_reference_keys',
            'fillable' => ['first_reference_key', 'second_reference_key', 'volume'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'TraitCompatibilityPolarity' => [
            'table' => 'trait_compatibility_polarity',
            'fillable' => ['reference_key', 'volume'],
            'hidden' => ['created_at', 'updated_at']
        ],
        'PurchasedItems' => [
            'table' => 'purchased_items',
            'fillable' => ['user_id', 'item_name', 'item_price', 'purchased_from'],
            'hidden' => ['created_at', 'updated_at']
        ],

        'SignupScreen' => [
            'table' => 'signup_screens',
            'fillable' => ['screen_name', 'description', 'screen_type'],
            'hidden' => ['created_at', 'updated_at']
        ],
    ]
];
