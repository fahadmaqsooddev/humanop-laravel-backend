<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/login';
    protected $ApiClientController = 'App\Http\Controllers\Api\ClientController';
    protected $PaymentClientController = 'App\Http\Controllers\Api\ClientController\Billing';
    protected $GamificationClientController = 'App\Http\Controllers\Api\ClientController\Gamification';
    protected $PlaylistClientController = 'App\Http\Controllers\Api\ClientController\PlayList';
    protected $HumanOpShopController = 'App\Http\Controllers\Api\ClientController\HumanOPShop';
    protected $UploadControllerNamespace = 'App\Http\Controllers';
    protected $HumanNetworkNamespace = 'App\Http\Controllers\Api\ClientController\HumanNetwork';

    protected $FamilyMatrixNamespace = 'App\Http\Controllers\Api\ClientController\FamilyMatrix';



    //V4 Controllers

    protected $PaymentClientControllerV4 = 'App\Http\Controllers\Api\v4\ClientController\Billing';
    protected $GamificationClientControllerV4 = 'App\Http\Controllers\Api\v4\ClientController\Gamification';
    protected $PlaylistClientControllerV4 = 'App\Http\Controllers\Api\v4\ClientController\PlayList';
    protected $HumanOpShopControllerV4 = 'App\Http\Controllers\Api\v4\ClientController\HumanOPShop';
    protected $HumanNetworkNamespaceV4 = 'App\Http\Controllers\Api\v4\ClientController\HumanNetwork';
    protected $FamilyMatrixNamespaceV4 = 'App\Http\Controllers\Api\v4\ClientController\FamilyMatrix';

    protected $ApiClientControllerV4 = 'App\Http\Controllers\Api\v4\ClientController';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin_routes/admin_web.php'));

            Route::middleware('web')
                ->prefix('media')
                ->namespace($this->UploadControllerNamespace)
                ->group(base_path('routes/file_routes/file_routes.php'));

            // Api's

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/auth/auth_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/payment/old_payment_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->PaymentClientController)
                ->group(base_path('routes/client_apis/payment/new_payment_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/dashboard/dashboard_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/assessment/assessment_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/user_profile/user_profile_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/library_resources/library_resource_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->HumanOpShopController)
                ->group(base_path('routes/client_apis/humanop_shop/humanop_shop_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->HumanNetworkNamespace)
                ->group(base_path('routes/client_apis/post/post_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->HumanNetworkNamespace)
                ->group(base_path('routes/client_apis/story/story_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/messages/message_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->HumanNetworkNamespace)
                ->group(base_path('routes/client_apis/human_network/human_network_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/notification/notification_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/credits/credits_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->GamificationClientController)
                ->group(base_path('routes/client_apis/gamifications/gamifications_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->PlaylistClientController)
                ->group(base_path('routes/client_apis/playlist/playlist_api.php'));

            Route::prefix('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/webhook/blue_webhook_api.php'));

            Route::prefix('api')
                ->namespace($this->FamilyMatrixNamespace)
                ->group(base_path('routes/client_apis/family_matrix/family_matrix_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace('')
                ->group(base_path('routes/client_apis/sport/sport_api.php'));



            // -------------------------
            // API V4 ROUTES
            // -------------------------

            // Auth
            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/auth/auth_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/payment/old_payment_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->PaymentClientControllerV4)
                ->group(base_path('routes/client_apis/v4/payment/new_payment_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/dashboard/dashboard_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/assessment/assessment_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/user_profile/user_profile_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/library_resources/library_resource_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->HumanOpShopControllerV4)
                ->group(base_path('routes/client_apis/v4/humanop_shop/humanop_shop_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->HumanNetworkNamespaceV4)
                ->group(base_path('routes/client_apis/v4/post/post_api.php'));

// Story
            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->HumanNetworkNamespaceV4)
                ->group(base_path('routes/client_apis/v4/story/story_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/messages/message_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->HumanNetworkNamespaceV4)
                ->group(base_path('routes/client_apis/v4/human_network/human_network_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/notification/notification_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/credits/credits_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->GamificationClientControllerV4)
                ->group(base_path('routes/client_apis/v4/gamifications/gamifications_api.php'));

            Route::prefix('api/v4')->middleware('api')
                ->namespace($this->PlaylistClientControllerV4)
                ->group(base_path('routes/client_apis/v4/playlist/playlist_api.php'));


            Route::prefix('api/v4')
                ->namespace($this->ApiClientControllerV4)
                ->group(base_path('routes/client_apis/v4/webhook/blue_webhook_api.php'));

            Route::prefix('api/v4')
                ->namespace($this->FamilyMatrixNamespaceV4)
                ->group(base_path('routes/client_apis/v4/family_matrix/family_matrix_api.php'));


            Route::prefix('api/v4')->middleware('api')
                ->namespace('')
                ->group(base_path('routes/client_apis/v4/sport/sport_api.php'));


        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
