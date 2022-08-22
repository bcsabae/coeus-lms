<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    /**
     * Find and return Course from parameter that can be either ID or slug.
     * If not found, 404 exception is rased
     * If more than one found, 500 is raised
     * If exactly one found, that Course model is returned
     *
     * @param $input
     * @return BlogPost|mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function findCourseIdSlug($input) {
        $foundPost = new BlogPost();
        //id was passed
        if(intval($input))
        {
            $post = BlogPost::findOrFail($input);
            $foundPost = $post;
        }
        //slug was passed
        else {
            //TODO: SQL injection check
            $post = BlogPost::where('slug', $input)->get();
            if($post->count() == 1) {
                $foundPost = $post[0];
            }
            else if($post->count() == 0) {
                abort(404);
            }
            else if($post->count() > 1) {
                abort(500);
            }
        }

        return $foundPost;
    }

    /**
     * PostsController constructor.
     * Only authenticated users can edit create or delete blog posts
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //pagination
        $posts = BlogPost::paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        //see if user can create blog post
        $user = Auth::user();
        $this->authorize('create-blogpost');

        $validated = $request->validated();
        $newPost = new BlogPost();
        $newPost->title = $validated['title'];
        $newPost->slug = Str::slug($validated['title']);
        $newPost->content = $validated['content'];
        $newPost->user_id = $user->id;

        $newPost->save();

        $request->session()->flash('status', 'Post created');

        return redirect()->route('blog.show', ['blog'=> $newPost->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->findCourseIdSlug($id);

        return view('posts.show', ['id' => $id, 'post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $foundPost = $this->findCourseIdSlug($id);
        return view('posts.edit', ['post' => $foundPost]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = $this->findCourseIdSlug($id);
        $this->authorize('update-blogpost', $post);
        $validated = $request->validated();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->save();

        $request->session()->flash('status', 'Post updated');

        return redirect()->route('blog.show', ['blog'=> $post->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->findCourseIdSlug($id);
        $this->authorize('update-blogpost', $post);
        $post->delete();

        session()->flash('status', 'Post deleted');

        return redirect(route('blog.index'));
    }
}
