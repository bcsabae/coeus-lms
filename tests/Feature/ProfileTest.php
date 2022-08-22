<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/**
 * Class ProfileTest
 * @package Tests\Feature
 *
 * Test cases:
 *
 * Only authenticated users can access views
 * Only authenticated users can modify a profile
 * Only users who have recently confirmed their passwords can modify a profile
 * Personal info update works
 * Password update works
 * Only good passwords can be given
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function init()
    {
        $this->testDatabaseUp();
        $this->getUsers();
    }

    /**
     * Test if only authenticated users can access the profile view. Also check password confirmation to access profile
     */
    public function testOnlyAuthenticatedUsersCanAccessViews()
    {
        $this->init();

        $user = $this->users['subscriber'];

        $routes = [
            'profile.show',
            'profile.password.view',
            'profile.delete.view'
        ];

        $route = $routes[2];

        //TODO: megcsinálni hogy minden route-t nézzen és ne csak egyet
        //not authenticated
        $response = $this->get(route($route));
        if($route == 'profile.password.show') dd(session()->get('auth'));
        $response->assertRedirect(route('login'));


        $this->actingAs($user);

        //authenticated, password not verified
        $response = $this->get(route($route));
        $response->assertRedirect(route('password.confirm'));

        //authenticated, password verified
        $response = $this
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route($route));
        $response->assertStatus(200);
        $response->assertSee($user->name);

        return;
    }

    public function testOnlyAuthenticatedUsersCanModifyAProfile()
    {
        $this->init();
        $postParams = [
            'name' => 'test',
            'email' => 'testuser@unit.com'
        ];

        //unauthenticated user cannot modify the profile
        $response = $this->post(route('profile.update'), $postParams);
        $response->assertRedirect(route('login'));

        $user = $this->users['subscriber'];

        //without recent password confirmation, an authenticated user cannot modify the profile
        $this->actingAs($user);
        $response = $this->post(route('profile.update'), $postParams);
        $response->assertRedirect(route('password.confirm'));

        //with recent password confirmation, modifying should work
        $this->actingAs($user);
        $response = $this
            ->withSession(['auth.password_confirmed_at'=> time()])
            ->post(route('profile.update'), $postParams);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', $postParams);

        return;
    }

    /**
     * This test sees if only authenticated users can modify a password.
     * It tries to set a new password while logged out, logged in but no recent password confirmation
     * and logged in with recent password confirmation
     */
    public function testOnlyAuthenticatedusersCanModifyPassword()
    {
        $this->init();
        $user = $this->users['subscriber'];
        $user->password = Hash::make('password');
        $user->save();
        $this->getUsers();

        $newPassword = 'abcdEFG1234';
        $postParams = [
            'current_password' => 'password',
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword
        ];

        //unauthenticated user cannot modify password
        $response = $this->post(route('profile.change-password'), $postParams);
        $response->assertRedirect(route('login'));

        //without recent password confirmation, an authenticated user cannot modify the password
        $this->actingAs($user);
        $response = $this->post(route('profile.change-password'), $postParams);
        $response->assertRedirect(route('password.confirm'));

        //with recent password confirmation, modifying should work
        $this->actingAs($user);
        $response = $this
            ->withSession(['auth.password_confirmed_at'=> time()])
            ->post(route('profile.change-password'), $postParams);
        $response->assertStatus(200);
        //dd(User::where('id', $user->id)->get()[0]->name, Hash::make($newPassword));
        $this->assertTrue(Hash::check($newPassword, $user->password));

        //with wrong password, one cannot set new one
        $user->password = Hash::make('password');
        $user->save();

        $postParams['current_password'] = 'dummy';
        $response = $this
            ->withSession(['auth.password_confirmed_at'=> time()])
            ->post(route('profile.change-password'), $postParams);
        $response->assertSessionHasErrors('current_password');
        $this->assertFalse(Hash::check($newPassword, $user->password));

        return;
    }

    /**
     * Test to see if only good passwords can be used as new passwords.
     * This test loops through a few example passwords and sees if that can be set
     * as a new password
     */
    public function testOnlyGoodPasswordsCanBeGiven()
    {
        $this->init();
        $user = $this->users['subscriber'];

        $testPasswords = [
            'simple' => false,
            'CaPiTaLlOnG' => false,
            'short' => false,
            '5H0rt' => false,
            'aA1!' => false,
            'aAbBcCdD' => false,
            'aAbBcCd6'=> true,
            'abcABC2!' => true
        ];

        foreach ($testPasswords as $password => $shouldPass)
        {
            //reset password
            $user->password = Hash::make('password');
            $user->save();

            $this->actingAs($user);

            //because post reponse redirects us to the previous page, this is needed
            $this->get(route('profile.password.view'));

            $postParams = [
                'current_password' => 'password',
                'new_password' => $password,
                'new_password_confirmation' => $password
            ];

            $response = $this
                ->followingRedirects()
                ->withSession(['auth.password_confirmed_at'=> time()])
                ->post(route('profile.change-password'), $postParams);

            if ($shouldPass)
            {
                $response->assertStatus(200);
                //if succeed, we're redirected to the profile page
                $response->assertSee($user->name);
                //password should be updated
                $this->assertTrue(Hash::check($password, $user->password));
            }
            else
            {
                $response->assertStatus(200);
                //if didn't succeed, we're back to the change password view
                $response->assertSee('Change password');
                //old password is still good
                $this->assertTrue(Hash::check('password', $user->password));
            }
        }
    }


}
