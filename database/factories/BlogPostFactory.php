<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\BlogPostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(6, true);
        return [
            /*'title' => $this->faker->words(6),
            'content' => $this->faker->paragraph(10),
            'category' => $this->faker->word(),
            'user_id' => 1,*/
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraph(10),
            'category' => $this->faker->word(),
            'user_id' => 1,
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
        return $this->afterCreating(function (BlogPost $blogPost){
            /*
             * Assign categories to blog posts
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
                    $blogPostCategory = new BlogPostCategory([
                        'blog_post_id' => $blogPost->id,
                        'category_id' => $randomCategoryId
                    ]);

                    $blogPostCategory->save();
                }
            }
        });
    }
}
