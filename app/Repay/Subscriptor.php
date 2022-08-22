<?php

namespace App\Repay;

use \Illuminate\Support\Facades\Facade;

/**
 * Class Subscriptor for easier access on SubscriptionCreator class
 * @package App\Repay
 *
 * @method App\Models\Subscription make()
 */
class Subscriptor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SubscriptionCreator::class;
    }
}
