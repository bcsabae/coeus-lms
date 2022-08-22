<?php

namespace App\Repay\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SimulatePaymentWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //send post request to localhost app
        $response = Http::post(route('repay.webhook'), [
            'payment_uid' => $this->payment->payment_id,
            'is_successful' => 'yes'
        ]);
        if($response->successful()) Log::channel('repay')->info(get_class($this). ': test payment API\'s answer is sent for payment '.$this->payment->id);
        if($response->failed()) Log::channel('repay')->info(get_class($this). ': test payment API\'s answer could not be sent for payment '.$this->payment->id.' status '.$response->status());
    }
}
