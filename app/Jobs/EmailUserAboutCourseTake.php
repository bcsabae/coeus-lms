<?php

namespace App\Jobs;

use App\Mail\CourseTakeNotification;
use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailUserAboutCourseTake implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $course;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Course $course)
    {
        //
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Mail::to($this->user)->send(
            new CourseTakeNotification($this->user, $this->course)
        );
    }
}
