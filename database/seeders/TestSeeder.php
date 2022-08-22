<?php

namespace Database\Seeders;

use App\Models\AccessRight;
use App\Models\Category;
use App\Models\Content;
use App\Models\CourseCategory;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeToAccessRight;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create access rights hardcoded
        $accessRights = [
            new AccessRight(['name' => 'admin']),
            new AccessRight(['name' => 'vip']),
            new AccessRight(['name' => 'member']),
            new AccessRight(['name' => 'guest'])
        ];

        foreach ($accessRights as $accessRight)
        {
            $accessRight->save();
            $st = new SubscriptionType(['name' => $accessRight->name]);

            $price = 0;
            switch ($st->name)
            {
                case 'admin':
                    $price = 0;
                    break;
                case 'vip':
                    $price = 4999;
                    break;
                case 'member':
                    $price = 2999;
                    break;
                case 'guest':
                    $price = 0;
                    break;
                default:
                    break;
            }

            $st->price = $price;
            $st->save();
        }

        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'admin')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'admin')->get()[0]->id
        ]);
        $sttar->save();
        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'vip')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'admin')->get()[0]->id
        ]);
        $sttar->save();
        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'member')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'admin')->get()[0]->id
        ]);
        $sttar->save();
        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'vip')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'vip')->get()[0]->id
        ]);
        $sttar->save();
        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'member')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'vip')->get()[0]->id
        ]);
        $sttar->save();
        $sttar = new SubscriptionTypeToAccessRight([
            'access_right_id' => AccessRight::where('name', 'member')->get()[0]->id,
            'subscription_type_id' => SubscriptionType::where('name', 'member')->get()[0]->id
        ]);
        $sttar->save();


        //create admin
        $user = new User(['name'=>'admin', 'email'=>'admin@test.test', 'password'=>Hash::make('password')]);
        $user->save();

        //create payment method for admin
        $adminPaymentMethod = new PaymentMethod([
            'user_id' => $user->id,
            'token' => "abcdefg123456789",
            'amount' => 2999,
            'valid_from' => now(),
            'valid_until' => now()->add('P12M'),
            'subscription_type_id' => SubscriptionType::where('name', 'admin')->first()->id,
            'card_type' => 'Visa',
            'last_four' => 1234
        ]);
        $adminPaymentMethod->save();

        $subscription = new Subscription([
            'user_id'=>$user->id,
            'subscription_types_id' => SubscriptionType::where('name', 'admin')->get()[0]->id,
            'start' => '1990-01-01',
            'end' => '2029-12-13',
            'status' => 'active'
        ]);
        $subscription->save();


        //create payment for admin
        $adminPayment = new Payment([
            'user_id' => $user->id,
            'payment_id' => "stripe_0122323425",
            'vendor' => 'stripe',
            'price' => 2999,
            'subscription_id' => $subscription->id,
            'status' => 'filled',
            'payment_intent_id' => null,
        ]);
        $adminPayment->save();


        //create vip
        $user = new User(['name'=>'vipUser', 'email'=>'vip@test.com', 'password'=>Hash::make('password')]);
        $user->save();

        $subscription = new Subscription([
            'user_id'=>$user->id,
            'subscription_types_id' => SubscriptionType::where('name', 'vip')->get()[0]->id,
            'start' => '1990-01-01',
            'end' => '2029-12-13'
        ]);
        $subscription->save();

        //create subscriber
        $user = new User(['name'=>'subscriberUser', 'email'=>'member@test.com', 'password'=>Hash::make('password')]);
        $user->save();

        $subscription = new Subscription([
            'user_id'=>$user->id,
            'subscription_types_id' => SubscriptionType::where('name', 'member')->get()[0]->id,
            'trial_start' => '2010-01-01',
            'start' => '2022-01-01',
            'end' => '2029-12-13'
        ]);
        $subscription->save();

        //create guest
        $user = new User(['name'=>'guestUser', 'email'=>'guest@test.com', 'password'=>Hash::make('password')]);
        $user->save();

        //create no subscription for this user

        //create categories
        \App\Models\Category::factory(5)->create();

        //create demo course here so it has the first ID
        $this->addDemoCourse();

        //create courses, two for each access group
        \App\Models\Course::factory(8)->create();
        Course::whereIn('id', [3, 4])->update(['access_right_id' => 2]);
        Course::whereIn('id', [5, 6])->update(['access_right_id'=> 3]);
        Course::whereIn('id', [7, 8])->update(['access_right_id' => 4]);

        //create blog posts
        \App\Models\BlogPost::factory(2)->create();
    }

    /**
     * @param $subscriptionTypes: array containing subscription types to add
     *  - admin
     *  - vip
     *  - subscriber
     * @param $subscriptionExpiry: array containing subscription expiries, directly mapped to $subscriptionTypes
     *  - expired
     *  - active
     *  - future
     *
     * @return bool
     */
    public function addUserWithSubscription($subscriptionTypes=[], $subscriptionExpiry=[])
    {
        //parameter checking
        //empty arrays default to active subscriber
        if(sizeof($subscriptionTypes) == 0)
        {
            $subscriptionTypes = ['subscriber'];
        }
        if(sizeof($subscriptionExpiry) == 0)
        {
            $subscriptionExpiry = ['active'];
        }

        //if just one parameter is passed, convert to array
        if(!is_array($subscriptionTypes))
        {
            $subscriptionTypes = [$subscriptionTypes];
        }
        if(!is_array($subscriptionExpiry))
        {
            $subscriptionExpiry = [$subscriptionExpiry];
        }

        //create user
        $user = User::factory()->make();
        $user->save();

        foreach ($subscriptionTypes as $index => $type)
        {
            //print('Now working on '.$type.' which is index '.$index.'\n');
            $newSubscription = new Subscription();
            $start = Carbon::now();
            $end = Carbon::now();

            //get access right, if falsely provided, default to subscriber
            $accessRight = AccessRight::where('name', $type)->get()[0];
            if($accessRight == null) $accessRight = AccessRight::where('name', 'subscriber')->get()[0];

            //if there is a match, adjust start and end dates accordingly
            if(array_key_exists($index, $subscriptionExpiry))
            {
                //print('Array key '.$index.' exists\n');
                switch ($subscriptionExpiry[$index])
                {
                    case 'expired':
                        //print('This is expired\n');
                        $start = $start->subDays(14);
                        $end = $end->subDays(7);
                        break;
                    case 'active':
                        $start = $start->subDays(7);
                        $end = $end->addDays(7);
                        break;
                    case 'future':
                        $start = $start->addDays(7);
                        $end = $end->addDays(14);
                        break;
                    default:
                        print('This is default\n');
                        $start = Carbon::yesterday();
                        $end = Carbon::tomorrow();
                        break;
                }
            }
            //if there is no match, default to an active subscription
            else
            {
                $start = Carbon::yesterday();
                $end = Carbon::tomorrow();
            }

            //save subscription to user
            $newSubscription->user_id = $user->id;
            $newSubscription->access_right_id = $accessRight->id;
            $newSubscription->start = $start;
            $newSubscription->end = $end;

            $newSubscription->save();
        }
        return true;
    }

    public function fillSubscriptions()
    {
        //all types with all type of expiries
        $this->addUserWithSubscription(['admin'], ['active']);
        $this->addUserWithSubscription(['admin'], ['expired']);
        $this->addUserWithSubscription(['admin'], ['future']);

        $this->addUserWithSubscription(['vip'], ['active']);
        $this->addUserWithSubscription(['vip'], ['expired']);
        $this->addUserWithSubscription(['vip'], ['future']);

        $this->addUserWithSubscription(['subscriber'], ['active']);
        $this->addUserWithSubscription(['subscriber'], ['expired']);
        $this->addUserWithSubscription(['subscriber'], ['future']);

        //active vip and subscriber
        $this->addUserWithSubscription(['vip', 'subscriber'], ['active', 'active']);

        //expired vip and subscriber
        $this->addUserWithSubscription(['vip', 'subscriber'], ['expired', 'expired']);

        //current subscriber with future vip
        $this->addUserWithSubscription(['vip', 'subscriber'], ['future', 'active']);

        //subscriber with no active subscriptions
        $this->addUserWithSubscription(['subscriber', 'subscriber'], ['future', 'active']);

        //vip with no active subscriptions
        $this->addUserWithSubscription(['vip', 'vip'], ['future', 'active']);

        //admin with no active subscriptions
        $this->addUserWithSubscription(['admin', 'admin'], ['future', 'active']);
    }

    public function addDemoCourse()
    {
        //create course
        $title = "Demo course";
        $description = "This course demonstrates how the page works and what its main features are. To take this course, click \"Take course\". This page needs to be refreshed to be able to navigate through parts of the course. You can only take the course if you have an account with proper rights and confirmed e-mail address.";
        $course = new Course([
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $description,
            'rating' => 5,
            'access_right_id' => 3,
            'length' => 10
        ]);
        $course->save();

        //create category for the course
        $category = new Category([
            'name' => 'demo'
        ]);
        $category->save();

        //put course in that category
        $courseCategory = new CourseCategory([
            'course_id' => $course->id,
            'category_id' => $category->id
        ]);
        $courseCategory->save();

        //add contents
        $title = "About this page";
        $description = "This page is an e-learning platform (almost ready). You can take and watch video courses. The system manages and tracks these courses, making a track of users' progress. Access is subscription based, there are three access rights: VIP, normal and guest.";
        $c = new Content([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $description,
            'number' => 1,
            'course_id' => $course->id
        ]);
        $c->save();

        $title = "About courses";
        $description = "A course starts with a greeting page. This describes what the course is about, as well as rating, duration, etc. Parts of each course are on the side menu.";
        $c = new Content([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $description,
            'number' => 2,
            'course_id' => $course->id
        ]);
        $c->save();

        $title = "Finishing parts of a course";
        $description = "A part of a coruse is finished immediately when the user opens the page. In the future, it might be added that the user has to reach the end of the course, maybe pass with a quiz about the contents. If you go the last part without finishing the others, you can see the contents, but nothing else.";
        $c = new Content([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $description,
            'number' => 3,
            'course_id' => $course->id
        ]);
        $c->save();

        $title = "Finishing courses";
        $description = "A course is finished, when all parts of that course is finished. Now, if every part is done, go to the last page, you will see a quiz. You need to finish this to finish the course.";
        $c = new Content([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $description,
            'number' => 4,
            'course_id' => $course->id
        ]);
        $c->save();

        $title = "Quiz";
        $description = "At the end of the course, there is a quiz. If you pass this, you have finished the course. You'll get an e-mail notification as well.";
        $c = new Content([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $description,
            'number' => 5,
            'course_id' => $course->id
        ]);
        $c->save();
    }
}
