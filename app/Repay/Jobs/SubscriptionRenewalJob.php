<?php

namespace App\Repay\Jobs;

use App\Models\PaymentIntent;
use App\Models\Subscription;
use App\Repay\Payer;
use App\Repay\Subscriptor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionRenewalJob implements ShouldQueue
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
        Log::channel('repay')->info(get_class($this) . ': created for subscription '. $subscription->id);
        $this->subscription = $subscription;
    }

    /**
     * Renew subscription:
     * Create payment intent (this is the first time for this subscription)
     * Intitiate recurring payment with the new payment intent
     *
     * @return void
     */
    public function handle()
    {
        Log::channel('repay')->info(get_class($this) . ': invoked for subscription '.$this->subscription->id);
        //get price of subscription
        $price = $this->subscription->price();
        //check if payment is necessary
        if($price > 0)
        {
            Log::channel('repay')->info(get_class($this) . ': Payment needed, initiating payment for subscription '. $this->subscription->id);
            //create payment intent
            $paymentIntent = Payer::paymentIntent($this->subscription);
            Log::channel('repay')->info(get_class($this) . ': Payment intent created for subscription '. $this->subscription.', with ID '.$paymentIntent->id);
            //initiate payment
            Payer::pay($paymentIntent);
        }
        else
        {
            Log::channel('repay')->info(get_class($this) . ': Payment not needed, renew subscription');
            //straight jump to renewal
            Subscriptor::renew($this->subscription);
        }
        Log::channel('repay')->info(get_class($this) . ': finished for subscription '.$this->subscription->id);
    }
}
