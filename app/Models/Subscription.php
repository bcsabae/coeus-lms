<?php

namespace App\Models;

use App\Repay\HandlesSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends Model
{
    use HasFactory, HandlesSubscription;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subscription_types_id',
        'trial_start',
        'start',
        'end',
        'status',
        'recurring_ends'
    ];

    /*public function accessRight()
    {
        return $this->belongsTo('App\Models\AccessRight');
    }*/

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function type()
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_types_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentIntent()
    {
        return $this->hasOne(PaymentIntent::class);
    }

    public function price()
    {
        return $this->type->price;
    }

    public function scopeNeedsRenewal(Builder $query)
    {
        //a subscription needs renewal if the end date is today or in the past,
        //and its status is active
        //and it isn't about to expire (else it is in the expiring state)
        return $query->where('status', 'active')
            ->whereDate('end', '<=', now()->toDateString())
            ->whereDate('recurring_ends', '>', now()->add(config('repay.expiry_notice')));
    }

    public function scopeExpires(Builder $query)
    {
        //a subscription is soon to expire if the expiry date is at most repay.expiry_note days away
        return $query->where('status', 'active')
            ->whereDate('recurring_ends', '<=', now()->add(config('repay.expiry_notice')));
    }

    public function scopeExpired(Builder $query)
    {
        return $query->whereDate('recurring_ends', '<=', now());
    }

    public function scopeTrialExpires(Builder $query)
    {
        //a subscription is soon to expire from trial if the status is trial
        //and start date is past now (should have started)
        return $query->where('status', 'trial')
            ->whereDate('start', '>=', now());
    }

    public function scopeNeedsRetry(Builder $query)
    {
        return $query->select(['subscriptions.*'])
            ->from('subscriptions')
            ->join('payment_intents',
                'payment_intents.subscription_id',
                '=',
                'subscriptions.id')
            ->where('payment_intents.next_attempt', '<', now());
    }
}
