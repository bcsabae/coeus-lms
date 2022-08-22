<?php

namespace App\Repay;

use App\Models\AccessRight;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Repay\SubscriptionCreator;

trait CanSubscribe
{
    public function hasSubscriptionType(SubscriptionType $subscriptionType)
    {
        //loop through all subscriptions and see if any matches the given type
        foreach ($this->subscription as $actSubscription)
        {
            //only count active subscriptions
            if($actSubscription->lives)
            {
                if ($actSubscription->type == $subscriptionType) return true;
            }
        }
    }

    public function hasTrial(SubscriptionType $subscriptionType)
    {
        foreach($this->subscription as $actSubscription)
        {
            if($actSubscription->type == $subscriptionType)
            {
                if($actSubscription->isTrial()) return true;
            }
        }
    }

    public function canAccess(AccessRight $accessRight)
    {
        foreach($this->subscription as $actSubscription)
        {
            if($actSubscription->canAccess($accessRight)) return true;
        }
        return false;
    }

    //return (first) active subscription type
    public function activeSubscription()
    {
        if(count($this->subscription))
        {
            return $this->subscription[0]->type;
        }
        else return null;
    }

    public function subscribe(SubscriptionType $subscriptionType)
    {
        Subscriptor::make($this, $subscriptionType, true);
    }

    public function cancelSubscription(Subscription $subscription)
    {
        Subscriptor::cancel($subscription);
    }

    public function deleteSubscription(Subscription $subscription)
    {
        Subscriptor::delete($subscription);
    }
}
