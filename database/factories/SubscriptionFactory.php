<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-1 years', 'now');
        $end = $this->faker->dateTimeBetween('now', '+1 years');

        //start should always be before end
        if($start > $end){
            $tmp = $start;
            $start = $end;
            $end = $tmp;
        }

        return [
            'start' => $start,
            'end' => $end
        ];
    }
}
