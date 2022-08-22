<?php

namespace App\Providers;

use App\Repay\PaymentHandler;
use App\Repay\SubscriptionCreator;
use App\Repay\SubscriptionHandler;
use App\Repay\SubscriptionScheduler;
use App\Repay\TestPaymentApi;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        //use Bootstrap for pagination
        Paginator::useBootstrap();

        //SubscriptionCreator from Repay
        $this->app->singleton(SubscriptionCreator::class, function ($app) {
           return new SubscriptionCreator(
               config('repay.subscription_period'),
               config('repay.trial_period'),
               config('repay.recurring_period')
           );
        });

        //PaymentHandler from Repay
        $this->app->singleton(PaymentHandler::class, function ($app) {
            return new PaymentHandler(new TestPaymentApi());
        });

        //SubscriptionScheduler from Repay
        $this->app->bind(SubscriptionScheduler::class, function ($app) {
            return new SubscriptionScheduler();
        });

        //SubscriptionHandler from Repay
        $this->app->bind(SubscriptionHandler::class, function ($app) {
            return new SubscriptionHandler();
        });
    }
}
