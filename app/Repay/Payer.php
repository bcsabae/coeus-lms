<?php

namespace App\Repay;

use \Illuminate\Support\Facades\Facade;

/**
 * Class Payer for easier access on PaymentHandler class
 * @package App\Repay
 */
class Payer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentHandler::class;
    }
}
