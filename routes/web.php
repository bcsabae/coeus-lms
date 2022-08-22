<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ContentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
----------------------------------------
Authentication routes:
  Index
  Landing page
----------------------------------------
*/
Auth::routes();


/*
----------------------------------------
Home pages:
  Index
  Landing page
----------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

//landing page
Route::get('/home', function () {
    return view('home');
})->name('home');


/*
----------------------------------------
About pages:
  Introduction
  FAQ
----------------------------------------
*/

//Introduction
Route::get('/about', function () {
    return view('about.index');
})->name('about.index');

//FAQ
Route::get('/faq', function () {
    return view('about.faq');
})->name('about.faq');

/*
----------------------------------------
Course pages
----------------------------------------
*/

//CRUD resource implemetation
Route::resource('courses', CoursesController::class)
    ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

//taking course route
Route::post('courses/takeCourse', 'App\Http\Controllers\CoursesController@takeCourse')->name('courses.take');

Route::get('/course/{course:slug}/content/{content:slug}', [ContentsController::class, 'show'])
    ->name('content.show');


/*
----------------------------------------
Blog pages:
  Blog post
----------------------------------------
*/

//CRUD resource implemetation
Route::resource('blog', PostsController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

/*
----------------------------------------
Account/management pages:
  Account
  Learning
  Subscription
----------------------------------------
*/

//Index
Route::get('/profile', function () {
    return "Profile page";
})->name('management.index');

//Learning
Route::get('/curriculum', function () {
    return "Curriculum page";
})->name('management.curriculum');

//Subscription
Route::get('/plans', [PaymentController::class, 'renderSubscriptionsView'])->name('plans');

Route::get('/test-pay', [PaymentController::class, 'renderCheckoutView']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//email confirmation notice
Route::get('email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

//e-mail confirmation
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return view('auth.verify-email-successful', ['user' => $request->user()]);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



//profile management

Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.show');

Route::post('/profile', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.update');

Route::get('/profile/billing', [ProfileController::class, 'billing'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.billing');

Route::get('/profile/subscriptions', [SubscriptionsController::class, 'showUserSubscriptions'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.subscriptions');

Route::get('/profile/change-password', [ProfileController::class, 'changePasswordView'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.password.view');

Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.change-password');

Route::get('/profile/delete', function() {
    return view('profile.delete-profile');
})->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.delete.view');

Route::post('/profile/delete', [ProfileController::class, 'delete'])
    ->middleware('auth')
    ->middleware('password.confirm')
    ->name('profile.delete');

Route::get('/confirm-password', [ProfileController::class, 'confirmPasswordView'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ProfileController::class, 'confirmPassword'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('password.confirm');

//password reset
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'show'])
    //->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'email'])
    //->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'show'])
    //->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'update'])
    //->middleware('guest')
    ->name('password.update');

Route::get('/password-update-successful', function() {
    return view('auth.password-update-successful');
})->middleware('auth')->name('password.status');

//learning

Route::get('/learning', function () {
    return 0;
})->name('learning');


//repay
Route::get('/pay/{id}', function($id) {
    return view('repay.dummy-pay', ['id' => $id]);
})->name('repay.pay');
Route::get('/test', function () { return 'anyád';});

