<?php

namespace App\Repay;

use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\User;
use App\Repay\Events\LastPaymentFailedEvent;
use App\Repay\Events\PaymentFailedEvent;
use App\Repay\Events\PaymentSuccessfulEvent;
use Illuminate\Database\Eloquent\Model;
//use \Illuminate\Http\Client\Request;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Repay\Jobs\SimulatePaymentWebhookJob;

class PaymentHandler
{
    protected $paymentInterface;

    public function __construct(PaymentApi $paymentInterface)
    {
        $this->paymentInterface = $paymentInterface;
    }

    /**
     * Create payment intent from a subscription
     * @param Subscription $subscription
     * @return PaymentIntent
     */
    public function paymentIntent(Subscription $subscription): PaymentIntent
    {
        //create payment intent
        $paymentIntent = new PaymentIntent([
            'price' => $subscription->price(),
            'last_attempt' => now(),
            'next_attempt' => Subscriptor::nextPayment(now()),
            'remaining_retries' => config('repay.payment_retries'),
            'subscription_id' => $subscription->id,
            'uid' => $this->getPaymentUid()
        ]);
        $paymentIntent->save();
        return $paymentIntent;
    }

    /**
     * Delay payment intent by default period, as well as substract one from remaining retries
     * @param PaymentIntent $paymentIntent
     */
    public function delayPaymentIntent(PaymentIntent $paymentIntent): void
    {
        Log::channel('repay')->info(get_class($this). ': delaying payment intent '.$paymentIntent->id);
        $paymentIntent->next_attempt = Subscriptor::nextPayment(now());
        $acualRetires = $paymentIntent->remaining_retries;
        if($acualRetires != 0)
        {
            $paymentIntent->remaining_retries = $acualRetires-1;
        }
        $paymentIntent->save();
    }

    /**
     * Initiate the process of payment for a payment intent
     * @param PaymentIntent $paymentIntent
     */
    public function pay(PaymentIntent $paymentIntent): void
    {
        Log::channel('repay')->info(get_class($this). ': paying paymentIntent '.$paymentIntent->id);
        //call API for payment
        $apiResponse = $this->sendPayment($paymentIntent);

        //if payment is sent successfully, create payment for pending payment
        if($apiResponse['success'] === true)
        {
            Log::channel('repay')->info(get_class($this). ': payment successfully sent to API for '.$paymentIntent->id);
            $pendingPayment = new Payment([
                'user_id' => $paymentIntent->subscription->user->id,
                'payment_id' => $apiResponse['payment_id'],
                'vendor' => $this->paymentInterface->vendor,
                'price' => $paymentIntent->price,
                'status' => 'pending',
                'subscription_id' => $paymentIntent->subscription->id,
                'payment_intent_id' => $paymentIntent->id
            ]);

            $pendingPayment->save();

            //if testing, simulate post request from API to app's webhook
            if(get_class($this->paymentInterface) == TestPaymentApi::class)
            {
                Log::channel('repay')->error(get_class($this). ': initiating webhook simulation for payment '.$pendingPayment->id);
                SimulatePaymentWebhookJob::dispatch($pendingPayment);
            }
        }
        else
        {
            Log::channel('repay')->error(get_class($this). ': sending payment to API failed for paymentIntent '.$paymentIntent->id);
            //if sending payment was not successful, check if there is any more retrires for payment intent
            if($paymentIntent->remaining_retries == 0)
            {
                //if there are no more reties, delete payment intent and fire last payment failed event
                $subscription = $paymentIntent->subscription();
                $paymentIntent->delete();
                LastPaymentFailedEvent::dispatch($subscription);
            }
            else
            {
                //if there are more payment retries, fire payment failed event and udpate payment intent
                PaymentFailedEvent::dispatch($paymentIntent->subscription);
                $this->delayPaymentIntent($paymentIntent);
            }
        }

    }

    /**
     * Send payment to the implemented API
     * Return array containing the necessary API responses (see interface implementations)
     * @param PaymentIntent $paymentIntent
     * @return array
     */
    public function sendPayment(PaymentIntent $paymentIntent): array
    {
        return $this->paymentInterface->sendPayment($paymentIntent);
    }

    /**
     * Webhook handler for API back-calls.
     * Forward request to API wrapper.
     * @param Request $request
     */
    public function webhook(Request $request): void
    {
        Log::channel('repay')->info(get_class($this). ': recieved payment API response');
        $this->paymentInterface->receivePaymentWebhook($request);
    }

    /**
     * Handler for the API wrapper to call back when received answer from API
     * @param PaymentIntent $paymentIntent
     * @param string $status
     * @param string $vendor_id
     */
    public function receivePaymentAnswer(PaymentIntent $paymentIntent, string $status, string $vendor_id): void
    {
        Log::channel('repay')->info(get_class($this). ': got answer from payment API for paymentIntent '.$paymentIntent->id);
        if($status == 'success')
        {
            Log::channel('repay')->info(get_class($this). ': answer from payment API is successful for paymentIntent '.$paymentIntent->id);
            $this->fillIntent($paymentIntent, $vendor_id);
        }
        else if($status == 'failed')
        {
            Log::channel('repay')->error(get_class($this). ': answer from payment API is not successful for paymentIntent '.$paymentIntent->id);
            $this->failIntent($paymentIntent);
        }
    }

    /**
     * Fill a payment intent after successful payment
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    public function fillIntent(PaymentIntent $paymentIntent, string $vendor_id): void
    {
        //update payment
        $payment = $paymentIntent->payment;
        $payment->status = 'filled';
        $payment->payment_id = $vendor_id;
        $payment->payment_intent_id = null;
        $payment->save();

        //delete payment intent
        $paymentIntent->delete();

        //fire payment successful event
        Log::channel('repay')->info(get_class($this). ': payment ' . $payment->payment_id . ' filled');
        PaymentSuccessfulEvent::dispatch($payment);
    }

    /**
     * Fail payment intent if payment from bank failed
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    public function failIntent(PaymentIntent $paymentIntent): void
    {
        //update payment
        $payment = $paymentIntent->payment;
        $payment->status = 'failed';
        $payment->save();

        //if payment was not successful, check if there is any more retrires for payment intent
        if($paymentIntent->remaining_retries == 0)
        {
            //if there are no more reties, delete payment intent and fire last payment failed event
            $subscription = $paymentIntent->subscription();
            $paymentIntent->delete();
            LastPaymentFailedEvent::dispatch($payment);

        }
        else
        {
            //if there are more payment retries, fire payment failed event and udpate payment intent
            PaymentFailedEvent::dispatch($payment);
            $this->delayPaymentIntent($paymentIntent);
        }

        Log::channel('repay')->info(get_class($this). ': payment ' . $payment->payment_id . ' failed');
    }

    /**
     * Schedule a later retry for the intent
     * @param PaymentIntent $paymentIntent
     * @return bool
     */
    public function retryIntent(PaymentIntent $paymentIntent): bool
    {
        //if this was the last retry
        if($paymentIntent->remaining_retries == 0)
        {
            //delete payment intent and fire last payment failed event
            $subsciption = $paymentIntent->subscription;
            $paymentIntent->delete();
            LastPaymentFailedEvent::dispatch($subsciption);
            return false;
        }
        //else update dates and return true
        else
        {
            $paymentIntent->last_attempt = now();

        }
    }

    /**
     * Function to create a UID for a payment
     * @return string
     */
    public function getPaymentUid(): string
    {
        return sha1(rand());
    }

    /**
     * Guard to check if payment needs to be initiated.
     * @param Subscription $subscription
     * @return bool
     */
    public function needsPayment(Subscription $subscription): bool
    {
        //if price is zero, no payment is needed
        if($subscription->price() == 0)
        {
            return false;
        }

        //if there is an unfinished payment, do not initiate new one
        if($subscription->paymentIntent)
        {
            return false;
        }

        return true;
    }

    public function test()
    {
        $pi = $this->paymentIntent(Subscription::find(1));
        $this->pay($pi);
    }
}
