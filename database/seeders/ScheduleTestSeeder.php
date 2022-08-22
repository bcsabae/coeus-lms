<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use App\Models\User;
use App\Repay\SubscriptionCreator;
use App\Repay\Subscriptor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ScheduleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //make default test seed
        $ts = new TestSeeder();
        $ts->run();

        //dummy types for readability
        $trial = true;
        $noTrial = false;

        //make additional member users with different subscriptions
        $i = 0;
        $subType = SubscriptionType::where('name', 'member')->first();
        //create users
        $users = [];
        for($i = 0; $i < 10; $i++)
        {
            $user = new User([
                'name' => 'TestUser'.$i,
                'email' => 'testuser'.$i.'@test.com',
                'password' => Hash::make('password')
            ]);
            $user->save();
            $users[$i] = $user;
        }

        //create subscriptions

        //one that is freshly created with trial
        Subscriptor::make($users[0], $subType, $trial);

        //one that is freshly created without trial
        Subscriptor::make($users[1], $subType, $noTrial);

        //one that is in trial and will expire tomorrow
        Subscriptor::make($users[2], $subType, $trial, now()->sub(config('repay.trial_period'))->add('P1D'));

        //one that is not trial and will expire tomorrow
        Subscriptor::make($users[3], $subType, $noTrial, now()->sub(config('repay.trial_period'))->add('P1D'));

        //one that is expired already
        Subscriptor::make($users[4], $subType, $noTrial, now()->sub(config('repay.trial_period'))->sub('P1D'));

        //one that will start tomorrow with trial
        Subscriptor::make($users[5], $subType, $trial, now()->add('P1D'));

        //one that will start tomorrow without trial
        Subscriptor::make($users[6], $subType, $noTrial, now()->add('P1D'));
    }
}
