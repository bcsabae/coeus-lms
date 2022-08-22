<?php

namespace App\Repay\Jobs;

use App\Models\Subscription;
use App\Repay\Notifications\SubscriptionExpiringSoonNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringJob implements ShouldQueue
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
     * Subscription is expiring.
     * Send a notification for the user about the expiry soon
     *
     * @return void
     */
    public function handle()
    {
        //TODO: notification
        $this->subscription->user->notify(new SubscriptionExpiringSoonNotification($this->subscription));
    }
}
