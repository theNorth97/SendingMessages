<?php

namespace App\Providers;

use App\Services\EmailValidator;
use App\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('App\Services\SubscriptionService', function ($app) {
            return new SubscriptionService(new EmailValidator());
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
