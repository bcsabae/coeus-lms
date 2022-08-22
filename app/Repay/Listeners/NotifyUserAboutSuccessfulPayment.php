<?php

namespace App\Repay\Listeners;

use App\Events\HasTakenCourse;
use App\Jobs\EmailUserAboutCourseTake;
use App\Repay\Notifications\LastPaymentFailedNotification;
use App\Repay\Events\PaymentSuccessfulEvent;
use App\Repay\Notifications\PaymentSuccessfulNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyUserAboutSuccessfulPayment
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
        $user = $event->payment->user;
        $user->notify(new PaymentSuccessfulNotification($event->payment));
    }
}
