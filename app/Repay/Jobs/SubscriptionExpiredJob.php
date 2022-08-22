<?php

namespace App\Repay\Jobs;

use App\Models\Subscription;
use App\Repay\Notifications\SubscriptionExpiredNotification;
use App\Repay\Subscriptor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredJob implements ShouldQueue
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
     * Subscription expired.
     * Delete subscription and send notification to user
     *
     * @return void
     */
    public function handle()
    {
        //send notification
        $this->subscription->user->notify(new SubscriptionExpiredNotification($this->subscription));
        //delete subscription
        Subscriptor::delete($this->subscription);
    }
}
