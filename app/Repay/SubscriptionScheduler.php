<?php

namespace App\Repay;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class SubscriptionScheduler
{
    /**
     * Invokable function of class.
     * Simply call run method
     */
    public function __invoke()
    {
        $this->run();
    }

    /**
     * Run checks for all subscriptions. This function should be called periodically.
     * Tasks to perform:
     *  - collect subscriptions to renew and start the renewal process
     *  - collect subscriptions that are about to expire and fire event
     *  - retry and failed payments that are due time
     *  - monitor expiring trials and convert them to subscriptions
     */
    public function run()
    {
        Log::channel('repay-scheduler')->info('Running scheduler...');
        $this->renewal();
        $this->expiring();
        $this->retry();
        $this->expired();
        $this->trials();
    }

    /**
     * Fetch all subscriptions that need renewal, and initiate payment for them
     */
    public function renewal()
    {
        $subscriptions = Subscription::needsRenewal()->get();
        foreach($subscriptions as $subscription)
        {
            resolve(SubscriptionHandler::class)->renewal($subscription);
        }
    }

    /**
     * Fetch all subscriptions that are about to expire and initiate reminder process
     */
    public function expiring()
    {
        $subscriptions = Subscription::expires()->get();
        foreach($subscriptions as $subscription)
        {
            resolve(SubscriptionHandler::class)->expiring($subscription);
        }
    }

    /**
     * Fetch all subscriptions whose payments are due to retry, and re-initiate payment process
     */
    public function retry()
    {
        $subscriptions = Subscription::needsRetry()->get();
        foreach($subscriptions as $subscription)
        {
            resolve(SubscriptionHandler::class)->retry($subscription);
        }
    }

    /**
     * Fetch expired subscriptions and initiate expiry process
     */
    public function expired()
    {
        $subscriptions = Subscription::expires()->get();
        foreach($subscriptions as $subscription)
        {
            resolve(SubscriptionHandler::class)->expired($subscription);
        }
    }

    /**
     * Fetch expiring trials and start the conversion process
     */
    public function trials()
    {
        $subscriptions = Subscription::trialExpires()->get();
        foreach($subscriptions as $subscription)
        {
            resolve(SubscriptionHandler::class)->trials($subscription);
        }
    }

}
