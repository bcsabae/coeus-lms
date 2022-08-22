<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function courses()
    {
        return $this->hasMany('App/Course');
    }

    public function subscriptions()
    {
        return $this->hasMany('App/Subscription');
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function subscriptionTypes()
    {
        return $this->belongsToMany(SubscriptionType::class, 'subscription_type_to_access_rights');
    }
}
