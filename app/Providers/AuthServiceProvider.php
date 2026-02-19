<?php

namespace App\Providers;

use App\Models\Client\MessageThread\MessageThread;
use App\Policies\MessageThreadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        User::class => UsersPolicy::class,
        MessageThread::class => MessageThreadPolicy::class,
        \App\Models\v4\Client\MessageThread\MessageThread::class => \App\Policies\v4\MessageThreadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-items', 'App\Policies\UsersPolicy@manageItems');

        Gate::define('manage-users', 'App\Policies\UsersPolicy@manageUsers');
    }


}
