<?php

namespace Tests\Feature;

use App\Http\Livewire\TakeCourse;
use App\Models\Course;
use App\Models\CourseTake;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class CourseTakeTest extends TestCase
{
    use RefreshDatabase;

    private function courseNotTakenViewTest($response)
    {
        //if course is not taken, we should see the take course button
        $response->assertSeeText('Take course');
    }

    private function courseTakenViewTest($response)
    {
        //if course is taken, we should see the untake course button
        $response->assertSeeText('Untake course');
    }

    private function isCourseTaken($user, $course)
    {
        if(CourseTake::where('user_id', $user->id)->where('course_id', $course->id)->get()->count() == 1)
        {
            return true;
        }
        else return false;
    }

    private function takeCourseTest($course, $shouldSucceed)
    {
        if($shouldSucceed)
        {
            //user should be able to take course
            Livewire::test(TakeCourse::class, ['courseId' => $course->id])
                ->call('takeCourseToggle')
                //see if course take is toggled in Livewire controller
                ->assertSet('isTaken', true);
        }
        else
        {
            //user should not be able to take course
            Livewire::test(TakeCourse::class, ['courseId' => $course->id])
                ->call('takeCourseToggle')
                //see if course take is toggled in Livewire controller
                ->assertForbidden();
        }
    }

    public function testOnlySubscribedUsersCanTakeCourse()
    {
        $this->testDatabaseUp();
        $this->verifyAllEmails();

        //create new course
        $course = new Course();
        $course = $course->factory()->create();

        $users = $this->getUsers();

        //see if only users with subscription can see the take course button and can take the course
        foreach ($users as $right => $user)
        {
            $this->actingAs($user);

            //every user should be able to access the course, as courses are public for now
            $this->assertTrue(Gate::allows('access-course', $course));

            //if user has rights to access course, user should be able to take and untake course
            if($user->hasActiveSubscription($course->accessRight->id))
            {
                //get view
                $response = $this->get(route('courses.show', ['course' => $course]));

                //see if take course button is visible
                $this->courseNotTakenViewTest($response);

                //user should be able to take course
                Livewire::test(TakeCourse::class, ['courseId' => $course->id])
                    ->call('takeCourseToggle')
                    //see if course take is toggled in Livewire controller
                    ->assertSet('isTaken', true);

                //see if course is actually taken
                $this->assertTrue($this->isCourseTaken($user, $course));

                //reload page (not needed in browser, just for test)
                $response = $this->get(route('courses.show', ['course' => $course]));

                //see if untake button is visible
                $this->courseTakenViewTest($response);

                //user should be able to untake course
                Livewire::test(TakeCourse::class, ['courseId' => $course->id])
                    ->call('takeCourseToggle')
                    //see if course take is toggled in Livewire controller
                    ->assertSet('isTaken', false);

                //see if course is actually untaken
                $this->assertFalse($this->isCourseTaken($user, $course));
            }
            //else if user doesn't have active subscription, a button with link to plans should be rendered
            else if(! $user->hasActiveSubscription($course->accessRight->id))
            {
                //get view
                $response = $this->get(route('courses.show', ['course' => $course]));
                $response->assertSeeText('Take course');
            }
        }
    }
}
