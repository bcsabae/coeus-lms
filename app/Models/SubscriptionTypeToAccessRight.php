<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTypeToAccessRight extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_right_id',
        'subscription_type_id'
    ];

    public function accessRight()
    {
        return $this->belongsTo(AccessRight::class);
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }
}
