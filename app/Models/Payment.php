<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'vendor',
        'price',
        'subscription_id',
        'status',
        'payment_intent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentIntent()
    {
        return $this->belongsTo(PaymentIntent::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
