<?php

namespace Tests\Feature;

use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testPostsList()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testSeeOnePostWhenCreated()
    {
        $this->testDatabaseUp();

        //we need
        //we need a user for the blog post
        $user = User::where('id', 1)->get();
        $this->assertNotEmpty($user);
        $user = $user[0];

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        //create new post
        $post = new BlogPost();
        $post->title = 'Test title';
        $post->slug = Str::slug($post->title);
        $post->content = 'Test content';
        $post->user_id = $user->id;
        $post->save();

        //see if there is exactly one post visible
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSeeText([$post->title]);

        //check if blog post is accessible via slug
        $response = $this->get('/blog/'.$post->slug);
        $response->assertStatus(200);
        $response->assertSeeText([
            $post->title,
            $post->content
        ]);
    }

    public function testAllBlogPostsAvailableAndFetched()
    {
        $this->testDatabaseUp();


        $posts = BlogPost::all();

        foreach($posts as $post)
        {
            $response = $this->get('/blog/'.$post->slug);
            $response->assertStatus(200);
            $response->assertSeeText([
                $post->title,
                $post->content
            ]);
        }
    }

    public function testCreatePostViaRequest()
    {
        $this->testDatabaseUp();

        $user = User::where('id', 1);

        $title = 'Test title';
        $content = 'Test content';
        //test post
        $params = [
            'title' => $title,
            'content' => $content
        ];

        $users = $this->getUsers();

        //see if users can create a post
        foreach ($users as $right => $user)
        {
            $this->actingAs($user);
            $response = $this->post(route('blog.store', $params));
            //admin should be able to create post
            if($right == 'admin')
            {
                $response->assertStatus(302);
                $this->assertDatabaseHas('blog_posts', $params);
            }
            //other users should not be able to create a post
            else $response->assertStatus(403);
        }
    }

    public function testDeletePostViaRequest()
    {
        $this->testDatabaseUp();
        $this->getUsers();

        //see if users can delete a post
        foreach ($this->users as $right => $user)
        {
            $post = BlogPost::all()[0];
            $this->actingAs($user);
            $response = $this->delete(route('blog.destroy', ['blog' => $post]));
            //admin should be able to delete post
            if($right == 'admin')
            {
                $response->assertStatus(302);
                $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
            }
            //other users should not be able to delete a post
            else $response->assertStatus(403);
        }
    }

    public function testUpdatePostViaRequest()
    {
        $this->testDatabaseUp();
        $this->getUsers();

        //see if users can delete a post
        foreach ($this->users as $right => $user)
        {
            $post = BlogPost::all()[0];
            $modifiedParams = [
                'title' => $post->title . "_modified",
                'content' => $post->content . "_modified"
            ];
            $this->actingAs($user);
            $response = $this->put(route('blog.update', ['blog' => $post->slug]), $modifiedParams);
            //admin should be able to update post
            if($right == 'admin')
            {
                $response->assertStatus(302);
            }
            //other users should not be able to update a post
            else $response->assertStatus(403);
        }
    }
}
