<?php

namespace App\Providers;

use App\Models\AccessRight;
use App\Models\Content;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('access-course', function(User $user, Course $course) {
            //for now every course is public
            return true;
        });

        Gate::define('email-verified', function(User $user) {
            if($user->hasVerifiedEmail()) return true;
            else return false;
        });

        Gate::define('access-content', function(User $user, Content $content) {
            if(! Gate::allows('access-course', $content->course)) return false;
            if(! $user->course->contains($content->course)) return false;
            return true;
        });

        Gate::define('take-course', function(User $user, Course $course) {
            if($user->canAccess($course->accessRight)) return true;
            else return false;
        });

        //called before everything, first
        //a.k.a. superadmin gate
        Gate::before(function ($user, $ability){
            $admin_id = AccessRight::where('name', 'admin')->get()[0];
            if($user->canAccess($admin_id)) return true;
            //else return false;
        });

        //Can a user create a post
        Gate::define('create-blogpost', function($user) {
            //for now only admins can create blog posts
            if($user->accessRight->pluck('name')->contains('admin'))
            {
                return true;
            }
        });

        //Can a user udate or a post
        Gate::define('update-blogpost', function($user, $blogPost) {
            //for now only admins can create blog posts
            if($user->accessRight->pluck('name')->contains('admin'))
            {
                return true;
            }
        });

        //Can a user create a course
        Gate::define('create-course', function($user) {
            //for now only admins can create courses
            if($user->accessRight->pluck('name')->contains('admin'))
            {
                return true;
            }
        });

        //Can a user update or delete a course
        Gate::define('update-course', function($user, $course) {
            //for now only admins can modify courses
            if($user->accessRight->pluck('name')->contains('admin'))
            {
                return true;
            }
        });
    }
}
