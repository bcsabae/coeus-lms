<?php

namespace Tests\Feature;

use App\Models\AccessRight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Str;


class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function testCourseAvailableWhenCreated()
    {
        $this->testDatabaseUp();

        //create new course
        $course = new Course();
        $course = $course->factory()->create();

        //check if course is visible (go with admin, access rights are tested in an other test case)
        $admin_user = User::where('id', 1)->get()[0];
        $this->verifyEmail($admin_user);
        $this->actingAs($admin_user);
        $this->get(route('courses.show', ['course'=>$course->slug]))->assertStatus(200);


        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCreateCourseViaRequest()
    {
        $this->testDatabaseUp();
        $this->verifyAllEmails();

        $title = 'Test course';
        $params = [
            'title'=>$title,
            'slug' => Str::slug($title),
            'description'=>'Test content',
            'rating' => 5,
            'access_right_id' => 1
        ];

        $users = $this->getUsers();

        //see if users can create a course
        foreach ($users as $right => $user)
        {
            $this->actingAs($user);
            $response = $this->post(route('courses.store', $params));
            if($right == 'admin')
            {
                $response->assertStatus(302);
                $this->assertDatabaseHas('courses', $params);
            }
            else $response->assertStatus(403);
        }
    }

    public function testDeleteCourseViaRequest()
    {
        $this->testDatabaseUp();
        $this->verifyAllEmails();
        $this->getUsers();

        //see if users can delete a course
        foreach ($this->users as $right => $user)
        {
            $course = Course::all()[0];
            $this->actingAs($user);
            $response = $this->delete(route('courses.destroy', ['course' => $course]));
            //admin should be able to delete course
            if($right == 'admin')
            {
                $response->assertStatus(302);
                $this->assertDatabaseMissing('courses', ['id' => $course->id]);
            }
            //other users should not be able to delete a course
            else $response->assertStatus(403);
        }
    }

    public function testUpdateCourseViaRequest()
    {
        $this->testDatabaseUp();
        $this->verifyAllEmails();
        $this->getUsers();

        //see if users can delete a course
        foreach ($this->users as $right => $user)
        {
            $course = Course::all()[0];
            $modifiedParams = [
                'title' => $course->title . "_modified",
                'description' => $course->description . "_modified",
                'rating' => $course->rating,
                'access_right_id' => $course->access_right_id
            ];


            $this->actingAs($user);
            $response = $this->put(route('courses.update', ['course' => $course->id]), $modifiedParams);
            //admin should be able to update course
            if($right == 'admin')
            {
                $response->assertStatus(302);
            }
            //other users should not be able to update a course
            else $response->assertStatus(403);
        }
    }

    public function testOnlySubscribedUsersCanSeeCourses()
    {
        $this->testDatabaseUp();
        $this->verifyAllEmails();
        $this->getUsers();

        (new TestSeeder())->fillSubscriptions();

        foreach (Course::all() as $course)
        {
            foreach (User::all() as $user)
            {
                $this->actingAs($user);

                //see if the user has an active subscription to the course
                $hasActiveSubscription = $user->hasActiveSubscription($course->accessRight->id);

                //make request to the course index page
                $response = $this->get(route('courses.show', ['course' => $course]));

                //if user has subscription to the course, the course should be accessible and visible
                if($hasActiveSubscription)
                {
                    //if($response->status() != 200) dd($hasActiveSubscription, $user->id, $course->id);
                    $response->assertStatus(200);
                    $response->assertSeeText($course->title);
                }
                //admins should see everything
                else if($user->hasActiveSubscription(AccessRight::where('name', 'admin')->get()[0]->id))
                {
                    $response->assertStatus(200);
                    $response->assertSeeText($course->title);
                }
                //else if the user doesn't have active subscription to the course, a not authorized response
                //should be given - not true! should be visible in this case as well
                else
                {
                    $response->assertStatus(200);
                }

                //check access to all contents of the course as well
                foreach ($course->content as $content)
                {
                    //make request to the course index page
                    $response = $this->get(route('content.show', [
                        'course' => $course,
                        'content' => $content
                    ]));

                    //if user has taken the course, the content should be accessible and visible
                    if($user->course->contains($course))
                    {
                        //if($response->status() != 200) dd($response->getStatusCode());
                        $response->assertStatus(200);
                        $response->assertSeeText($content->title);
                    }
                    //admins should see everything
                    else if($user->hasActiveSubscription(AccessRight::where('name', 'admin')->get()[0]->id))
                    {
                        $response->assertStatus(200);
                        $response->assertSeeText($content->title);
                    }
                    //else if the user is not authorized to access the course, give 403
                    else if(Gate::forUser($user)->denies('access-course', $course))
                    {
                        $response->assertStatus(403);
                    }
                    //else if the user doesn't have taken the course, a redirect to the parent course's page should happen
                    else if(! $user->course->contains($course))
                    {
                        $response->assertRedirect(route('courses.show', ['course' => $course->id]));
                    }
                    else $this->throwException(500);
                }
            }
        }
    }
}
