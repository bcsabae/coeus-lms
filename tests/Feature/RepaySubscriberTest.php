<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionType;
use DateInterval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use App\Repay\Subscriptor;

class RepaySubscriberTest extends TestCase
{
    use RefreshDatabase;

    protected function init()
    {
        $this->testDatabaseUp();
        $this->getUsers();
        $this->getCourses();
    }

    private function checkSameDay($fromWhat, $what, $period = 'P0D')
    {
        $fromWhat = new Carbon($fromWhat);
        $what = new Carbon($what);
        $period = new DateInterval($period);

        return $fromWhat->add($period)->isSameDay($what);
    }

    private function checkSubscriptionDates($subscription, $trial_start, $start, $end, $recurring_ends)
    {
        if(false)
        {
            dump($subscription);
            dump('Trial start: '. ($trial_start ? $trial_start->toDateString() : 'null'));
            dump('Start: '.$start->toDateString());
            dump('End: '.$end->toDateString());
            dump('Recurring ends: '.$recurring_ends->toDateString());
        }

        return (
            //trial start date
            ($trial_start
                ? $this->checkSameDay($subscription->trial_start, $trial_start)
                : ($subscription->trial_start == null)) &&
            //subscription start date
            $this->checkSameDay($subscription->start, $start) &&
            //subscription end date
            $this->checkSameDay($subscription->end, $end) &&
            //recurring end date
            $this->checkSameDay($subscription->recurring_ends, $recurring_ends)
        );
    }

    public function testSubscriptionCreation()
    {
        $this->init();
        $user = $this->users['admin'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //new subscription is in the database
        $fetchedSubscription = Subscription::find($subscription->id);
        $this->assertTrue($fetchedSubscription != null);
        //trial starts today
        $this->assertTrue($this->checkSameDay(now(), $fetchedSubscription->trial_starts));
        //start is the default trial period away
        $this->assertTrue($this->checkSameDay(now(), $fetchedSubscription->start, config('repay.trial_period')));
        //end is the default period away from start
        $this->assertTrue($this->checkSameDay($fetchedSubscription->start, $fetchedSubscription->end, config('repay.subscription_period')));
        //recurring period is set correctly
        $this->assertTrue($this->checkSameDay(now(), $fetchedSubscription->recurring_ends, config('repay.recurring_period')));
        //subscription type is correct
        $this->assertTrue($fetchedSubscription->type == $subType);
        //user is the test user
        $this->assertTrue($fetchedSubscription->user == $user);
        //status is active
        $this->assertTrue($fetchedSubscription->status == 'active');
    }

    public function testSubscriptionCreationWithoutTrial()
    {
        $this->init();
        $user = $this->users['admin'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType, false);

        //new subscription is in the database
        $fetchedSubscription = Subscription::find($subscription->id);
        $this->assertTrue($fetchedSubscription != null);

        $this->assertTrue($this->checkSubscriptionDates(
            $fetchedSubscription,
            null,
            now(),
            now()->add(config('repay.subscription_period')),
            now()->add(config('repay.recurring_period'))
        ));

        //subscription type is correct
        $this->assertTrue($fetchedSubscription->type == $subType);
        //user is the test user
        $this->assertTrue($fetchedSubscription->user == $user);
        //status is active
        $this->assertTrue($fetchedSubscription->status == 'active');
    }

    public function testSubscriptionCreationWithSpecificStartingDate()
    {
        $this->init();
        $user = $this->users['admin'];
        $subType = SubscriptionType::find(2);

        //create subscription at a later date
        $offset = new DateInterval('P3M');
        $subscription = Subscriptor::make($user, $subType, true, now()->add($offset));

        //new subscription is in the database
        $fetchedSubscription = Subscription::find($subscription->id);
        $this->assertTrue($fetchedSubscription != null);

        $this->assertTrue($this->checkSubscriptionDates(
            $fetchedSubscription,
            now()->add($offset),
            now()->add($offset)->add(config('repay.trial_period')),
            now()->add($offset)->add(config('repay.trial_period'))->add(config('repay.subscription_period')),
            now()->add($offset)->add(config('repay.recurring_period'))
        ));

        //subscription type is correct
        $this->assertTrue($fetchedSubscription->type == $subType);
        //user is the test user
        $this->assertTrue($fetchedSubscription->user == $user);
        //status is active
        $this->assertTrue($fetchedSubscription->status == 'active');
    }

    public function testSkipTrial()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //test if it is a trial
        $this->assertTrue($subscription->isTrial());

        //before skipping date check
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            now(),
            now()->add(config('repay.trial_period')),
            now()->add(config('repay.trial_period'))->add(config('repay.subscription_period')),
            now()->add(config('repay.recurring_period'))
        ));

        //skip trial
        Subscriptor::skipTrial($subscription);

        //check dates again - attention: this test adds end and recurring end based on the previous differences in
        // DAYS, so in edge cases this can fail
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            null,
            now(),
            now()->add(config('repay.subscription_period')),
            now()->add(config('repay.recurring_period'))
        ));
    }
    public function testCancellingSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //cancel subscription
        Subscriptor::cancel($subscription);

        //check status
        $this->assertTrue($subscription->status == 'grace');
    }
    public function testDeletingSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //cancel subscription
        Subscriptor::delete($subscription);

        //check status
        $this->assertTrue($subscription->status == 'deleted');
    }

    public function testUpdatingSubscriptionDates()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //update dates for a later start and same duration
        //this should offset the start and end the same amount
        $oldSub = $subscription;
        $offset = new DateInterval('P2W');
        $newStart = now()->add($offset);
        Subscriptor::updateDates($subscription, $newStart);
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            $oldSub->trial_start,
            $newStart,
            $newStart->add(config('repay.subscription_period')),
            $oldSub->recurring_ends
        ));
    }

    public function testUpdatingSubscriptionDatesWithDuration()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);

        //update dates for a later start and same duration
        //this should offset the start and end the same amount
        $oldSub = $subscription;
        $offset = new DateInterval('P2W');
        $duration = 'P2M';
        $newStart = now()->add($offset);
        Subscriptor::updateDates($subscription, $newStart, $duration);
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            $oldSub->trial_start,
            $newStart,
            $newStart->add($duration),
            $oldSub->recurring_ends
        ));
    }

    public function testExtendingSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create default subscription
        $subscription = Subscriptor::make($user, $subType);
        $oldSub = $subscription;

        $duration = new DateInterval('P3M');

        Subscriptor::extend($subscription, $duration);

        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            $oldSub->trial_start,
            $oldSub->start,
            $oldSub->end->add($duration),
            $oldSub->recurring_ends
        ));
    }

    public function testRenewingActiveSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create subscription without trial
        $subscription = Subscriptor::make($user, $subType, false);
        $oldSub = $subscription;

        //renew subscription
        Subscriptor::renew($subscription);

        //check dates
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            null,
            now(),
            $oldSub->end->add(config('repay.subscription_period')),
            $oldSub->recurring_ends
        ));
    }

    public function testRenewingExpiringSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create subscription without trial, that expires today
        $subscription = Subscriptor::make($user, $subType, false, now()->sub(config('repay.subscription_period')));
        $oldSub = $subscription;

        //renew subscription
        Subscriptor::renew($subscription);

        //check dates
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            null,
            now(),
            now()->add(config('repay.subscription_period')),
            $oldSub->recurring_ends
        ));
    }

    public function testRenewingExpiredSubscriptions()
    {
        $this->init();
        $user = $this->users['vip'];
        $subType = SubscriptionType::find(2);

        //create subscription without trial, that has expired already
        $subscription = Subscriptor::make($user, $subType, false, now()->sub(config('repay.subscription_period'))->sub('P2W'));
        //emulate expiration
        $subscription->status = 'expired';
        $subscription->save();
        $oldSub = $subscription;

        //renew subscription
        Subscriptor::renew($subscription);

        //check dates
        $this->assertTrue($this->checkSubscriptionDates(
            $subscription,
            null,
            now(),
            now()->add(config('repay.subscription_period')),
            $oldSub->recurring_ends
        ));

        //check if status is updated
        $this->assertTrue($subscription->status == 'active');
    }
}
