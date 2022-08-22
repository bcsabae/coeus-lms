<?php

namespace Database\Factories;

use App\Http\Controllers\CoursesController;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseDependency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Content;
use Illuminate\Support\Facades\DB;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(6, true);
        return [
            'title' => $title,
            //'title' => $this->faker->realText(32, 1),
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(10),
            //'category' => $this->faker->word(),
            'rating' => $this->faker->numberBetween(0, 5),
            'access_right_id' => 3,
            'length' => $this->faker->numberBetween(15, 300)
        ];
    }

    /**
     * Configure the model factory
     *
     * @return $this
     */
    public function configure()
    {
        //create content for the course
        return $this->afterCreating(function (Course $course) {
            $numberOfContent = rand(1, 10);

            for($i=1; $i<=$numberOfContent; $i++)
            {
                Content::factory()->create([
                   'number' => $i,
                   'course_id' => $course->id
                ]);
            }
        })->afterCreating(function (Course $course) {
            /*
            * Create dependencies with the following logic:
            * First $baseCourseNumber courses are base courses with no dependency
            * After that, each course gets a random number of dependency from the base courses
            */

            $baseCourseNumber = 10;
            $minDependencyNum = 0;
            $maxDependencyNum = 5;

            if($course->id <= $baseCourseNumber) return;
            else
            {
                $courses = Course::count();
                $dependencyNum = rand($minDependencyNum, $maxDependencyNum);
                for($i=0; $i<$dependencyNum; $i++)
                {
                    $dependencyId = random_int($baseCourseNumber+1, $courses);
                    if($dependencyId == $course->id) continue;
                    else
                    {
                        $dependency = new CourseDependency(['course_id' => $course->id, 'dependency_id' => $dependencyId]);
                        $dependency->save();
                    }
                }
            }
        })->afterCreating(function (Course $course){
            /*
             * Assign categories to courses
             * Number of categories is a random value between 0 and $maxCategories
             */
            $maxCategories = 3;
            $numCategories = rand(0, $maxCategories);
            $idsSoFar = array();
            for($i=0; $i<$numCategories; $i++)
            {
                $randomCategoryId = DB::table('categories')->inRandomOrder()->first()->id;

                //filter duplicates
                if(in_array($randomCategoryId, $idsSoFar)) continue;
                else
                {
                    array_push($idsSoFar, $randomCategoryId);
                    $courseCategory = new CourseCategory([
                        'course_id' => $course->id,
                        'category_id' => $randomCategoryId
                    ]);

                    $courseCategory->save();
                }
            }
        });
    }
}
