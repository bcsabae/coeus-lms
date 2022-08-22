<?php

namespace App\Repay;

use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use DateTime;
use DateInterval;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class SubscriptionCreator
{
    protected $defaultDuration;
    protected $durationType;
    protected $trialPeriod;
    protected $recurringPeriod;

    /**
     * SubscriptionCreator constructor. Accepts the default duration specified in months (period can be specified
     * in the second parameter)
     * @param $defaultDuration
     * @throws \Exception
     */
    public function __construct($defaultDuration = 'P1M', $trial = 'P1M', $recurring = 'P12M')
    {
        try
        {
            $this->defaultDuration = new DateInterval($defaultDuration);
        }
        catch (Exception $exception)
        {
            $this->defaultDuration = new DateInterval('P1M');
            Log::error('Error setting Repay SubscriptionCreator default duration, '.$defaultDuration.' provided.
            Error in file '.$exception->getFile().' line '.$exception->getLine().'. Message: '.$exception->getMessage());
        }

        try
        {
            $this->trialPeriod = new DateInterval($trial);
        }
        catch (Exception $exception)
        {
            $this->trialPeriod = new DateInterval('P1M');
            Log::error('Error setting Repay SubscriptionCreator default trial duration, '.$trial.' provided.
            Error in file '.$exception->getFile().' line '.$exception->getLine().'. Message: '.$exception->getMessage());
        }

        try
        {
            $this->recurringPeriod = new DateInterval($recurring);
        }
        catch (Exception $exception)
        {
            $this->recurringPeriod = new DateInterval('P12M');
            Log::error('Error setting Repay SubscriptionCreator default recurring period, '.$recurring.' provided.
            Error in file '.$exception->getFile().' line '.$exception->getLine().'. Message: '.$exception->getMessage());
        }

    }

    /**
     * Create a new subscription
     *
     * @param User $user
     * @param SubscriptionType $subscriptionType
     * @param bool $trial
     * @param null $start
     * @param null $duration
     * @return Subscription
     */
    public function make(User $user, SubscriptionType $subscriptionType, $trial = true, $start = null, $duration = null)
    {
        //start date is not specified
        if($start == null)
        {
            if($trial)
            {
                $trialStart = now();
                $start = now()->add($this->trialPeriod);
            }
            else
            {
                $trialStart = null;
                $start = now();
            }
        }
        //start date is specified
        else
        {
            if($trial)
            {
                $trialStart = $start->copy();
                $start->add($this->trialPeriod);
            }
            else
            {
                $trialStart = null;
            }
        }
        if($duration == null) $end = $start->copy()->add($this->defaultDuration);
        else $end = $start->copy()->add(new DateInterval($duration));

        $subscription = new Subscription([
            'start' => $start,
            'end' => $end,
            'subscription_types_id' => $subscriptionType->id,
            'user_id' => $user->id,
            'recurring_ends' => ($trialStart ? $trialStart : $start)->copy()->add($this->recurringPeriod),
            'status' => 'active',
            'trial_start' => $trialStart
            ]);

        $subscription->save();

        return $subscription;
    }

    /**
     * Skip trial period and immediately start the subscription
     *
     * @param Subscription $subscription
     * @return Subscription|false
     * @throws \Exception
     */
    public function skipTrial(Subscription $subscription)
    {
        if(! $subscription->isTrial()) return false;
        $start = new Carbon($subscription->start);
        $end = new Carbon($subscription->end);
        $subscription->trial_start = null;
        $this->updateDates($subscription, now(), $start->diff($end));
        return $subscription;
    }

    /**
     * Cancel subscription, put it into grace status. Skip trial, if any.
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function cancel(Subscription $subscription)
    {
        $subscription->trial_start = null;
        $subscription->status = 'grace';
        return($subscription->save());
    }

    /**
     * Delete subscription, put it into deleted status. Skip trial, if any.
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function delete(Subscription $subscription)
    {
        $subscription->trial_start = null;
        $subscription->status = 'deleted';
        return($subscription->save());
    }

    /**
     * Update start and end date of subscription. If duration is provided, end will be the new date plus
     * the duration specified in DateInterval instance or string format. If it is not provided, duration will be the default duration.
     * If new start date is an earlier time, the new start date will be now.
     * @param Subscription $subscription
     * @param $newStart
     * @param null $duration
     * @return Subscription
     */
    public function updateDates(Subscription $subscription, $newStart, $duration = null)
    {
        if($newStart < now()) $newStart = now();
        if($duration == null) $newEnd = $newStart->copy()->add($this->defaultDuration);
        else $newEnd = $newStart->copy()->add($duration);
        $subscription->start = $newStart;
        $subscription->end = $newEnd;

        $subscription->save();

        return $subscription;
    }

    /**
     * Extend subscription. Add duration to the end of the subscription, or default duration
     *
     * @param Subscription $subscription
     * @param null $duration
     * @return Subscription
     */
    public function extend(Subscription $subscription, $duration = null)
    {
        $end = new Carbon($subscription->end);

        if($duration == null) $end->add($this->defaultDuration);
        else $end->add($duration);

        $subscription->end = $end;
        $subscription->save();

        return $subscription;
    }

    /**
     * Renew subscription. Extend the end date from now or the original end date, whichever is later.
     * If no duration is provided, use default duration. If currently on trial period, skip trial.
     *
     * @param Subscription $subscription
     * @param null $duration
     * @return Subscription
     */
    public function renew(Subscription $subscription, $duration = null)
    {
        $end = new Carbon($subscription->end);

        // if subscription has expired, start is now, end is counted from now
        if($end < now())
        {
            $start = now();
            if($duration == null) $end = $start->copy()->add($this->defaultDuration);
            else $end = $start->copy()->add($duration);
        }
        // if duration has not expired, start is now, but the end is from the original expiry
        else
        {
            $start = now();
            if($duration == null) $end = $end->copy()->add($this->defaultDuration);
            else $end = $end->copy()->add($duration);
        }

        $subscription->start = $start;
        $subscription->end = $end;
        $subscription->status = 'active';
        $subscription->save();

        return $subscription;
    }

    /**
     * Get next payment for a subscription. Any business logic can be implemented here.
     * By default, use the configured interval.
     *
     * @param Carbon $lastTry
     * @return Carbon
     */
    public function nextPayment(Carbon $lastTry): Carbon
    {
        return $lastTry->add(config('repay.payment_retry_period'));
    }
}
