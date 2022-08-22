<?php

namespace App\Repay;

use App\Models\Payment;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Log;

class TestPaymentApi implements \App\Repay\PaymentApi
{
    public $vendor;

    public function __construct()
    {
        $this->vendor = 'RepayTester';
    }

    public function sendPayment(\App\Models\PaymentIntent $paymentIntent): array
    {
        Log::channel('repay')->info(get_class($this). ': sending payment to API for paymentIntent '.$paymentIntent->id);
        $paymentId = random_int(1000000, 9999999);
        $success = true;
        Log::channel('repay')->info(get_class($this). ': payment successfully sent to API for paymentIntent '.$paymentIntent->id);
        return [
            'payment_id' => $paymentId,
            'success' => $success
        ];
    }

    public function receivePaymentWebhook(\Illuminate\Http\Request $request): void
    {
        //dummy payment ID that the API returns
        $paymentId = $request['payment_uid'];
        if($request['is_successful'] == 'yes') $result = 'success';
        if($request['is_successful'] == 'no') $result = 'failed';
        //result of the payment, TODO: to be created with more detail
        Log::channel('repay')->info(get_class($this). ': payment webhook got info: '.$paymentId);
        //get the paymentIntent for the id
        $payment = Payment::where('payment_id', $paymentId)->first();
        if($payment == null)
        {
            Log::channel('repay')->error(get_class($this). ': payment not found: '.$paymentId);
            return;
        }
        $paymentIntent = $payment->paymentIntent;
        if($paymentIntent == null)
        {
            Log::channel('repay')->error(get_class($this). ': paymentIntent not found for payment: '.$paymentId);
            //throw new RepayPaymentUidNotFoudException($paymentId);
            return;
        }
        Payer::receivePaymentAnswer($paymentIntent, $result, $paymentId);
    }
}
