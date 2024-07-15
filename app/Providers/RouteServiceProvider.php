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
    protected $ClientUserNamespace = 'App\Http\Controllers\User';
    protected $PractitionerNamespace = 'App\Http\Controllers\User';
    protected $EnterpriseNamespace = 'App\Http\Controllers\User';
    protected $ApiClientController = 'App\Http\Controllers\Api\ClientController';


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
                ->namespace($this->ClientUserNamespace)
                ->group(base_path('routes/admin_routes/client_user_web.php'));

            Route::middleware('web')
                ->namespace($this->PractitionerNamespace)
                ->group(base_path('routes/admin_routes/practitioner_web.php'));

            Route::middleware('web')
                ->namespace($this->EnterpriseNamespace)
                ->group(base_path('routes/admin_routes/enterprise_web.php'));


            // Api's

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/auth/auth_api.php'));

            Route::prefix('api')->middleware('api')
                ->namespace($this->ApiClientController)
                ->group(base_path('routes/client_apis/payment/payment_api.php'));

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
