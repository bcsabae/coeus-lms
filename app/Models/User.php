<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Repay\CanPay;
use App\Repay\CanSubscribe;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CanPay, CanSubscribe;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->hasMany('App\Models\Subscription');
    }

    public function blogPost()
    {
        return $this->hasMany('App\Models\BlogPost');
    }

    public function courseTake()
    {
        return $this->hasMany(CourseTake::class);
    }

    public function finishedContent()
    {
        return $this->hasMany(FinishedContent::class);
    }

    public function course()
    {
        return $this->belongsToMany(Course::class, 'course_takes');
    }

    public function accessRight()
    {
        return $this->belongsToMany(AccessRight::class, 'subscriptions');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasActiveSubscription($access_right_id)
    {
        foreach ($this->subscription()->get() as $subscription) {
            if ($subscription->access_right_id == $access_right_id) {
                if (($subscription->start <= now()) && ($subscription->end >= now())) {
                    //user has active subscription for the given access right
                    return true;
                }
            }
        }
        return false;
    }



    //Notifications
    public function sendPasswordResetNotification($token)
    {
        $url = route('password.reset', ['token' => $token]);
        $this->notify(new ResetPasswordNotification($url));
    }
}
