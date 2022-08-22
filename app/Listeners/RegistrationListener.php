<?php

namespace App\Listeners;

use App\Jobs\NotifyAdminOfNewRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegistrationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //TODO: create global admin user
        NotifyAdminOfNewRegistration::dispatch($event->user);
    }
}
