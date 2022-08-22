<?php

namespace Database\Factories;

use App\Models\AccessRight;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model factory
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $accessRightId = AccessRight::where('name', 'subscriber')->get()[0]->id;

            $hasSubscription = rand();
            $subscriptionProbability = 0.7;

            if($hasSubscription < getrandmax()*$subscriptionProbability) {
                $user->subscription()->save(Subscription::factory('App\Subscription')->make([
                    //subscription for the new user
                    'user_id' => $user->id,
                    'access_right_id' => $accessRightId
                ]));
            }
        });
    }
}
