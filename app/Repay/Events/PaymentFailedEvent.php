<?php

namespace App\Repay\Events;

use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\Subscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentFailedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        Log::channel('repay')->info(get_class($this). ': firing payment failed notification for payment '.$payment->id);
        $this->payment = $payment;
    }

    public function handle()
    {
        Log::channel('repay')->info(get_class($this). ': payment ' . $this->payment->payment_id . ' failed');
    }
}
