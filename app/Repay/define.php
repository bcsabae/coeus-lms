<?php

/**
 * Class Repay
 *
 * Class for handling subscription and payment logic
 */
class Repay
{
    //subscription logic
    //MODELS:
    //extend Users via CanSubscribe
        // hasSubscription(type)
        // hasTrial(type)
        // canAccess(access_right)
        // relations: hasMany subscription, activesubscription

    //create Subscriptions
        // fields: user, type, start, end, trial_start
        // relations: belongsTo user, hasOne type, hasOne paymentMethod, hasOne payment
        // methods:
        // lives(), expired(), isTrial(), recurring_ends()
        // create(), delete(), pay(), canAccess(access_right)

    //create SubscriptionTypes
        // fields: name, access_right
        // relations: hasMany access_right
        // methods:
        //
        //

    //create AccessRight
        // fields: id, name

    //create PaymentMethod
        // relations: belongsTo user
        // fields: user, token, amount, expiry

    //CLASSES:
    //create Payment
        // method: pay
        // webhooks




}
