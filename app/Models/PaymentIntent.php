<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor',
        'uid',
        'price',
        'last_attempt',
        'next_attempt',
        'remaining_retries',
        'subscription_id',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeNeedsRetry(Builder $query)
    {
        //a paymentintent needs retry if the next payment is due date
        //and retries is not null
        return $query->whereDate('next_attempt', '<=', now())
            ->where('remaining_retries', '>', 0);
    }
}
