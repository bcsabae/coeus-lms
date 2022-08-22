<?php

namespace App\Providers;

use App\Events\HasTakenCourse;
use App\Listeners\NotifyUserAboutCourseTake;
use App\Listeners\RegistrationListener;
use App\Repay\Events\LastPaymentFailedEvent;
use App\Repay\Events\PaymentFailedEvent;
use App\Repay\Events\PaymentSuccessfulEvent;
use App\Repay\Listeners\DeleteSubscription;
use App\Repay\Listeners\NotifyUserAboutFailedPayment;
use App\Repay\Listeners\NotifyUserAboutLastPaymentFailed;
use App\Repay\Listeners\NotifyUserAboutSuccessfulPayment;
use App\Repay\Listeners\RenewSubscription;
use App\Repay\Notifications\LastPaymentFailedNotification;
use App\Repay\Notifications\RecurringPaymentFailedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            RegistrationListener::class
        ],
        HasTakenCourse::class => [
            NotifyUserAboutCourseTake::class
        ],
        //Repay events
        PaymentSuccessfulEvent::class => [
            NotifyUserAboutSuccessfulPayment::class,
            RenewSubscription::class
        ],
        PaymentFailedEvent::class => [
            NotifyUserAboutFailedPayment::class
        ],
        LastPaymentFailedEvent::class => [
            NotifyUserAboutLastPaymentFailed::class,
            DeleteSubscription::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
