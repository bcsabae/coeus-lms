<?php

namespace Database\Seeders;

use App\Models\AccessRight;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //create access rights hardcoded
        $accessRights = [
            new AccessRight(['name' => 'admin']),
            new AccessRight(['name' => 'vip']),
            new AccessRight(['name' => 'subscriber'])
        ];

        foreach ($accessRights as $accessRight)
        {
            $accessRight->save();
        }

        //create admin
        $user = new User(['name'=>'admin', 'email'=>'admin@test.test', 'password'=>Hash::make('password')]);
        $user->save();

        $subscription = new Subscription([
            'user_id'=>$user->id,
            'access_right_id' => AccessRight::where('name', 'admin')->get()[0]->id,
            'start' => '1990-01-01',
            'end' => '2029-12-13'
        ]);
        $subscription->save();

        //create the rest
        \App\Models\User::factory(20)->create();
        \App\Models\Category::factory(5)->create();
        \App\Models\Course::factory(53)->create();
        \App\Models\BlogPost::factory(42)->create();
    }
}
