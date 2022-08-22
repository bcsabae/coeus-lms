<?php

namespace App\Repay;

use App\Models\PaymentMethod;
use App\Models\SubscriptionType;

trait CanPay
{
    public function paymentMethod()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Check if user has any saved payment method
     * @return bool
     */
    public function hasSavedPaymentMethod(SubscriptionType $subscriptionType): bool
    {
        //check if there is a valid payment method for the given user and subscription type
        $paymentMethods = PaymentMethod::validMethodsForUserAndSubscriptionType($this, $subscriptionType)->get();
        if(count($paymentMethods) == 0) return false;
        else return true;
    }
}
