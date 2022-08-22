<?php

namespace App\Repay;

use App\Models\PaymentIntent;
use Illuminate\Http\Request;

interface PaymentApi
{

    /**
     * Prepare and redirect to checkout page
     * @param PaymentIntent $paymentIntent
     */
    public function redirectToCheckoutPage(PaymentIntent $paymentIntent): void;

    /**
     * Send payment to the billing portal.
     * Return if the request was sent successfully.
     * @param PaymentIntent $paymentIntent
     * @return array containing the following keys:
     *      'payment_id': returned uid of transaction, or null if transaction refused
     *      'success': boolean indicating the result of sending the transaction
     */
    public function sendPayment(PaymentIntent $paymentIntent): array;

    /**
     * Webhook for payment portal notifications.
     * @param Request $request
     * @return bool
     */
    public function receivePaymentWebhook(Request $request): void;
}
