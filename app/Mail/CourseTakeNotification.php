<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseTakeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $course;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('garry@test.test', 'Test Agent')
            ->subject('You have taken a new course')
            ->view('emails.courses.coursetakenotification', [
                'user' => $this->user,
                'course' => $this->course
            ]);
    }
}
