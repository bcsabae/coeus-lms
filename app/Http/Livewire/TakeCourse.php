<?php

namespace App\Http\Livewire;

use App\Events\HasTakenCourse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Course;
use App\Models\CourseTake;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;


class TakeCourse extends Component
{
    public $courseId;

    //state indicating if the user has taken this course or not
    public $isTaken = null;

    public $debug = 0;

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }

    protected $listeners = ['courseTakeUpdate' => 'renderToggle'];

    public function renderToggle()
    {
        if($this->checkIfTaken($this->courseId) != $this->isTaken)
        {
            $this->isTaken = !($this->isTaken);
        }
    }

    private function checkIfTaken($courseId)
    {
        $foundCourse = Course::findOrFail($courseId);

        $user = Auth::user();
        if(App::runningUnitTests())
        {
            if(CourseTake::where('user_id', $user->id)->where('course_id', $this->courseId)->get()->count() == 1)
            {
                return true;
            }
            else return false;
        }
        return $user->course->contains($courseId);
    }
    public function takeCourseToggle()
    {
        $foundCourse = Course::findOrFail($this->courseId);
        $user = Auth::user();

        //authorize user to take or untake course
        Gate::authorize('access-course', $foundCourse);
        Gate::authorize('take-course', $foundCourse);
        Gate::authorize('email-verified');

        $this->debug++;

        if($this->checkIfTaken($this->courseId))
        {
            //course is already taken, untake course
            $success = CourseTake::where('user_id', $user->id)->where('course_id', $foundCourse->id)->delete();
            if($success)
            {
                $this->isTaken = false;
                $this->emit('courseTakeUpdate');
            }
        }
        else
        {
            //take course
            $courseTake = new CourseTake();
            $courseTake->user_id = $user->id;
            $courseTake->course_id = $foundCourse->id;
            if($courseTake->save())
            {
                //fire course taken event
                event(new HasTakenCourse($user, $foundCourse));

                //update controller
                $this->isTaken = true;
                $this->emit('courseTakeUpdate');
            }
        }
    }

    public function render()
    {
        if($this->isTaken === null)
        {
            $this->isTaken = $this->checkIfTaken($this->courseId);
        }
        return view('livewire.take-course');
    }
}
