<?php

namespace App\Repay;

use App\Models\Subscription;
use App\Repay\Jobs\SubscriptionExpiredJob;
use App\Repay\Jobs\SubscriptionExpiringJob;
use App\Repay\Jobs\SubscriptionRenewalJob;
use App\Repay\Jobs\SubscriptionRetryJob;
use App\Repay\Jobs\SubscriptionTrialExpiredJob;
use Illuminate\Support\Facades\Log;

/**
 * Class SubscriptionHandler to handle subscription related functionalities
 * @package App\Repay
 */
class SubscriptionHandler
{
    public function renewal(Subscription $subscription): void
    {
        Log::channel('repay')->info(get_class($this).': renewal initiated for subscription '.$subscription->id);
        SubscriptionRenewalJob::dispatch($subscription);
    }

    public function expiring(Subscription $subscription): void
    {
        Log::channel('repay')->info(get_class($this).': expiring job initiated for subscription '.$subscription->id);
        SubscriptionExpiringJob::dispatch($subscription);
    }

    public function retry(Subscription $subscription): void
    {
        Log::channel('repay')->info(get_class($this).': payment retry initiated for subscription '.$subscription->id);
        SubscriptionRetryJob::dispatch($subscription);
    }

    public function expired(Subscription $subscription): void
    {
        Log::channel('repay')->info(get_class($this).': expiration process initiated for subscription '.$subscription->id);
        SubscriptionExpiredJob::dispatch($subscription);
    }

    public function trials(Subscription $subscription): void
    {
        Log::channel('repay')->info(get_class($this).': trial termination initiated for subscription '.$subscription->id);
        SubscriptionTrialExpiredJob::dispatch($subscription);
    }
}
