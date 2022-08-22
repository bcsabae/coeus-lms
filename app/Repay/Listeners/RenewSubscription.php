<?php

namespace App\Repay\Listeners;

use App\Events\HasTakenCourse;
use App\Jobs\EmailUserAboutCourseTake;
use App\Mail\PaymentSuccessfulNotification;
use App\Repay\Events\PaymentSuccessfulEvent;
use App\Repay\SubscriptionCreator;
use App\Repay\Subscriptor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RenewSubscription
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentSuccessfulEvent $event)
    {
        $subscription = $event->payment->subscription;
        Log::channel('repay')->info(get_class($this). ': renewing subscription '.$subscription->id);
        Subscriptor::renew($subscription);
    }
}
