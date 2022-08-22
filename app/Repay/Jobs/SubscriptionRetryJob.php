<?php

namespace App\Repay\Jobs;

use App\Models\Subscription;
use App\Repay\Subscriptor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Repay;

class SubscriptionRetryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * A payment for a subscriptinon needs to be retried.
     * Fetch the payment intent for the subscription if there is any
     * Initiate payment for the PaymentIntent
     *
     * @return void
     */
    public function handle()
    {
        //retrieve payment intent, if none is found, throw exception
        $paymentIntent = $this->subscription->paymentIntent;

        //initiate payment, if payment is needed
        if($this->subscription->price() > 0)
        {
            Repay::pay($paymentIntent);
        }
        else
        {
            //if no payment needed, simply renew subscription
            Subscriptor::renew($this->subscription);
        }
    }
}
