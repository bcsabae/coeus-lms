<?php

namespace App\Listeners;

use App\Events\HasTakenCourse;
use App\Jobs\EmailUserAboutCourseTake;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserAboutCourseTake
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
    public function handle(HasTakenCourse $event)
    {
        //
        EmailUserAboutCourseTake::dispatch($event->user, $event->course);
    }
}
