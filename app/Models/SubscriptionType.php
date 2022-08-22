<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'access_right_id',
        'price'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscription_types_id');
    }

    public function accessRight()
    {
        return $this->belongsToMany(AccessRight::class, 'subscription_type_to_access_rights');
    }
}
