<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if($this->app->environment('development')) {
            URL::forceScheme('https');
        }

        $mailConfig = config('mail_config');

        Model::macro('decodeHtmlEntities', function ($attribute) {
            $value = $this->{$attribute};
            return html_entity_decode(stripslashes($value));
        });

        // Dynamically set the mail configuration
        Config::set('mail.from.address', $mailConfig['mail_address']);
        Config::set('mail.from.name', $mailConfig['mail_name']);
    }
}
