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

class SubscriptionDeleteJob implements ShouldQueue
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
     * Delete subscription.
     *
     *
     * @return void
     */
    public function handle()
    {
        //put subscription in deleted status
        $this->subscription->status = 'deleted';
        $this->subscription->save();
    }
}
