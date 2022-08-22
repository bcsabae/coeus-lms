<?php

namespace App\Http\Controllers;

use App\Models\FinishedContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

class ContentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $contentSlug
     * @return \Illuminate\Http\Response
     */
    public function show($courseSlug, $contentSlug)
    {
        $courseWithContents = Course::where('slug', $courseSlug)->with('content')->get()[0];

        //authorize user for accessing a content page
        $this->authorize('access-course', $courseWithContents);

        /*
         * get content with its parent course: course slug is unique, content slug is not
         * so we have to make an Eloquent Builder from the collection and filter for content
         * slug in that query
         */
        $contentWithCourse = $courseWithContents->content->toQuery()
            ->where('slug', $contentSlug)
            ->with('course')->get()[0];

        $course = $contentWithCourse->course;

        //see if user can access this content. If not, redirect to course page
        if(! Gate::allows('access-content', $contentWithCourse))
        {
            return redirect()->route('courses.show', ['course' => $course->id]);
        }

        //if user can access the page, create finished content for that page
        //no need to create if that already exists
        if(FinishedContent::where('user_id', auth()->user()->id)->where('content_id', $contentWithCourse->id)->count() === 0)
        {
            $finishedContent = new FinishedContent([
                'user_id' => auth()->user()->id,
                'content_id' => $contentWithCourse->id
            ]);
            $finishedContent->save();
        }

        return view('contents.show', [
            'content'=>$contentWithCourse,
            'course' =>$courseWithContents
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
