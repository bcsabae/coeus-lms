<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use App\Models\User;

/**
 * Class PaymentController
 *
 * Controller for site payments. This is a wrapper for different payment services.
 * Payment service is defined as a 'PAYMENT_SERVICE' environment variable.
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    public function once(User $user, Subscription $subscription) {}
    public function recurring(User $user, Subscription $subscription) {}
    public function success() {}
    public function failed() {}

    public function renderSubscriptionsView()
    {
        if(Auth::user())
        {
            if(Auth::user()->activeSubscription())
            {
                $activePlan = Auth::user()->activeSubscription()->name;
            }
            else $activePlan = 'guest';
        }
        else $activePlan = null;

        if(Auth::user()) $redirectTo = route('home');
        else $redirectTo = ['route' => route('register'), 'text' => 'Regisztráció'];

        return view('billing.plans', ['redirectTo' => $redirectTo, 'activePlan' => $activePlan]);
    }

    public function renderCheckoutView(SubscriptionType $subscriptionType)
    {
        $subscriptionType = SubscriptionType::where('name', 'vip')->first();
        $item = [
            'name' => $subscriptionType->name,
            'price' => $subscriptionType->price
        ];
        $user = Auth::user();

        //TODO: collect info about payment and render checkout form accordingly

        return view('billing.checkout', ['item' => $item, 'user' => $user]);
    }
}
