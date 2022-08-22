<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'amount',
        'valid_from',
        'valid_to',
        'subscription_type_id',
        'card_type',
        'last_four'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function scopeValidMethodsForUserAndSubscriptionType(Builder $query, User $user, SubscriptionType $subscriptionType)
    {
        return $query
            ->where('user_id', $user->id)
            ->where('subscription_type_id', $subscriptionType->id)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>', now());
    }
}
