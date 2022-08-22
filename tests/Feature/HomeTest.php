<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testHomePageIsUp()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testAboutPageIsUp()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }
    public function testFaqPageIsUp()
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
    }
    public function testCoursesPageIsUp()
    {
        $response = $this->get('/courses');

        $response->assertStatus(200);
    }

    public function testBlogPageIsUp()
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
    }


}
