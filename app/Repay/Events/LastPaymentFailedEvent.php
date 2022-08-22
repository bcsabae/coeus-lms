<?php

namespace App\Repay\Events;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LastPaymentFailedEvent
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
        Log::channel('repay')->info(get_class($this). ': firing last payment failed notification for payment '.$payment->id);
        $this->payment = $payment;
    }

    public function handle()
    {
        Log::channel('repay')->info(get_class($this). ': payment ' . $this->payment->id . ' failed for the last time.');
    }
}
