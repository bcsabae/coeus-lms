<?php

namespace Tests;

use App\Models\AccessRight;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\TestSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $users;
    protected $courses;

    /**
     * Fetch users of all types to be accessible in use cases
     * @return users
     */
    protected function getUsers()
    {
        $adminUser = User::all()[0];
        $vipUser = User::all()[1];
        $subscriptionUser = User::all()[2];
        $guestUser = User::all()[3];

        $this->users = [
            'admin' => $adminUser,
            'vip' => $vipUser,
            'subscriber' => $subscriptionUser,
            'guest' => $guestUser
        ];

        return $this->users;
    }

    public function getCourses()
    {
        $accessRightNames = [
            'admin',
            'vip',
            'member',
            'guest'
        ];

        foreach ($accessRightNames as $accessRightName)
        {
            $accessRight = AccessRight::where('name', $accessRightName)->get()[0];
            $this->courses[$accessRightName] = Course::where('access_right_id', $accessRight->id)
                ->get()[0];
        }

        return $this->courses;
    }


    /**
     * This function sets the test database up with minimal content defined in the test seeder.
     * This allows to test most basic functionalities.
     */
    protected function testDatabaseUp() {
        $seeder = new TestSeeder();
        $seeder->run();
    }

    /**
     * Verify email for user
     * @param User $user
     * @return User
     */
    protected function verifyEmail(User $user)
    {
        $user->markEmailAsVerified();
        return $user;
    }

    /**
     * Verify email for all users
     * @return bool
     */
    protected function verifyAllEmails()
    {
        $users = User::all();
        foreach($users as $user)
        {
            $this->verifyEmail($user);
        }
        return true;
    }

    protected function unVerifyAllEmails()
    {
        $users = User::all();
        foreach($users as $user)
        {
            $user->email_verified_at = null;
            $user->save();
        }
        return true;
    }


}
