<?php
namespace App\Providers;

use App\Support\StripeConfig;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->bind(StripeClient::class, function () {

            return new StripeClient(StripeConfig::secretKey());

        });

    }

    public function boot(): void {}
}
