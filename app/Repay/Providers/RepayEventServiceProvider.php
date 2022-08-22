<?php

namespace App\Repay\Providers;

use App\Repay\Events\SubscriptionRenewalEvent;
use Illuminate\Support\ServiceProvider;

class RepayEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscriptionRenewalEvent::class => [
            null,
        ],
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
