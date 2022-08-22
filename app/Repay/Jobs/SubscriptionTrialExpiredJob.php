<?php

namespace App\Repay\Jobs;

use App\Models\Subscription;
use App\Repay\Notifications\SubsctiptionTrialExpiredNotification;
use App\Repay\Subscriptor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionTrialExpiredJob implements ShouldQueue
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
     * Trial expired
     *
     * @return void
     */
    public function handle()
    {
        //activate subscription
        $this->subscription->activate();
        //send notification to user
        $this->subscription->user->notify(new SubsctiptionTrialExpiredNotification($this->subscription));
        //initiate first payment
        Subscriptor::renew($this->subscription);
    }
}
