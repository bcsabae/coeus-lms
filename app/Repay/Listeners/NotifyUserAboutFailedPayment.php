<?php

namespace App\Repay\Listeners;

use App\Notifications\PaymentFailedNotification;
use App\Repay\Events\PaymentFailedEvent;
use App\Repay\Notifications\RecurringPaymentFailedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserAboutFailedPayment
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
    public function handle(PaymentFailedEvent $event)
    {
        $user = $event->payment->user;
        $user->notify(new RecurringPaymentFailedNotification($event->payment));
    }
}
