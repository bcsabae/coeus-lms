<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 *
 * Controller for profile management. Desired functionality:
 *
 *
 * Only authenticated users can access views
 * Only authenticated users can modify a profile
 * Only users who have recently confirmed their passwords can modify a profile
 * Personal info update works
 * Password update works
 * Only good passwords can be given
 */
class ProfileController extends Controller
{
    /**
     * Render user profile alongside with forms to modify
     *
     * @param int userId
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', ['user' => $user]);
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return view('profile.show', ['user' => $user]);
    }

    public function changePasswordView()
    {
        $user = Auth::user();
        return view('profile.change-password', ['user' => $user]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return view('profile.show', ['user' => $user]);
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect('home');
    }

    public function confirmPasswordView()
    {
        return view('auth.confirm-password', [
            'loginFormEmail' => Auth::user()->email,
            'loginFormActionRoute' => route('password.confirm')
        ]);
    }

    public function confirmPassword(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['Helytelen jelszó']
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
