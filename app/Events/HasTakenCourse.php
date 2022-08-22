<?php

namespace App\Events;

use App\Models\Course;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HasTakenCourse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $course;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Course $course)
    {
        //
        $this->user = $user;
        $this->course = $course;
    }
}
