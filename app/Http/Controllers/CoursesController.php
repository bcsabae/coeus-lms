<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AccessRight;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use App\Models\CourseCategory;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    /**
     * CoursesController constructor.
     * Authentication for each function:
     *  Anyone can view the courses listing and the opening page, can play the intro video
     *  For unauthenticated users, the take course button or accessing any contents that are not public take to the
     *  login page
     *  For authenticated users, if the plan includes the course, they can take the course, if not, they are asked to
     *  upgrade the plan
     *
     * Email confirmation requirements: to take any course, the user needs to verify the email address
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except(['index', 'show']);
        $this->middleware('verified')
            ->except(['index', 'show']);
    }

    /**
     * Find and return Course from parameter that can be either ID or slug.
     * If not found, 404 exception is rased
     * If more than one found, 500 is raised
     * If exactly one found, that Course model is returned
     *
     * @param $input
     * @return Course|mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function findCourseIdSlug($input) {
        $foundCourse = new Course();
        //id was passed
        if(intval($input))
        {
            $course = Course::findOrFail($input);
            $foundCourse = $course;
        }
        //slug was passed
        else {
            //TODO: SQL injection check
            $course = Course::where('slug', $input)->get();
            if($course->count() == 1) {
                $foundCourse = $course[0];
            }
            else if($course->count() == 0) {
                abort(404);
            }
            else if($course->count() > 1) {
                abort(500);
            }
        }

        return $foundCourse;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categoryFilter = request()->input('category', null);
        $ratingFilter = request()->input('rating', null);
        $lengthFilter = request()->input('length', null);
        $dependenciesFilter = request()->input('dependency', null);
        $orderByRequest = request()->input('order', null);

        //base query to modify
        $query = Course::whereNotNull('id');

        if($categoryFilter)
        {
            //if there is only one category, convert to array
            if(! is_array($categoryFilter)) $categoryFilter = [$categoryFilter];
            //query for each category
            foreach ($categoryFilter as $actCategory)
            {
                $query = $query->whereHas('category', function($query) use ($actCategory) {
                    $query->where('name', $actCategory);
                });
            }
        }

        if($ratingFilter)
        {
            $query->where('rating', '>=', $ratingFilter);
        }

        if($dependenciesFilter)
        {
            if ($dependenciesFilter==1)
            {
                $query = $query->has('dependency');
            }
        }

        //order if needed
        if($orderByRequest)
        {
            switch ($orderByRequest)
            {
                case 'orderByLengthDesc':
                    $query = $query->orderBy('length', 'DESC');
                    break;
                case 'orderByLengthAsc':
                    $query = $query->orderBy('length', 'ASC');
                    break;
                //TODO: order by number of subscribers
                default:
                    break;
            }
        }
        //paginate
        $coursesWithCategory = $query->paginate(10)->withQueryString();

        //get all categories for the filter
        $allCategories = Category::whereHas('course')->get();

        return view('courses.index', ['courses' => $coursesWithCategory, 'categories' => $allCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-course');
        //get access rights
        $rights = AccessRight::all();
        $rightsOutputArray = array();
        foreach ($rights as $index=>$right) {
            $rightsOutputArray[$index] = array('id'=>$right->id, 'description'=>$right->description);
        }

        return view('courses.create', ['rights' => $rights]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourse $request)
    {
        //see if user can create a course
        $this->authorize('create-course');

        $validated = $request->validated();
        $newCourse = new Course();
        $newCourse->title = $validated['title'];
        $newCourse->slug = Str::slug($validated['title']);
        $newCourse->description = $validated['description'];
        //TODO: kategóriát itt kezelni
        //$newCourse->category = $validated['category'];
        $newCourse->rating = $validated['rating'];
        $newCourse->access_right_id = $validated['access_right_id'];

        $newCourse->save();

        $request->session()->flash('status', 'Course created');

        return redirect()->route('courses.show', ['course'=> $newCourse->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $foundCourse = $this->findCourseIdSlug($id);

        //get contents of the course
        $courseWithContentsAndUsers = Course::where('id', $foundCourse->id)->with('content')->with('user')->get()[0];

        //get categories of the course
        $categories = array();
        foreach(CourseCategory::where('course_id', $foundCourse->id)->with('category')->get() as $courseCategory)
        {
            array_push($categories, $courseCategory->category);
        }

        return view('courses.show', [
            'id' => $id,
            'course' => $courseWithContentsAndUsers,
            'categories' => $categories
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
        $foundCourse = $this->findCourseIdSlug($id);

        $this->authorize('update-course', $foundCourse);

        //get access rights
        $rights = AccessRight::all();
        $rightsOutputArray = array();
        foreach ($rights as $index=>$right) {
            $rightsOutputArray[$index] = array('id'=>$right->id, 'name'=>$right->name);
        }

        return view('courses.edit', ['course' => $foundCourse, 'rights' => $rightsOutputArray]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCourse $request, $id)
    {
        $newCourse = $this->findCourseIdSlug($id);

        $this->authorize('update-course', $newCourse);

        $validated = $request->validated();

        $newCourse->title = $validated['title'];
        $newCourse->slug = $slug = Str::slug($validated['title']);
        $newCourse->description = $validated['description'];
        $newCourse->rating = $validated['rating'];
        $newCourse->access_right_id = $validated['access_right_id'];


        $newCourse->save();

        $request->session()->flash('status', 'Course updated');

        return redirect()->route('courses.show', ['course'=> $newCourse->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $foundCourse = $this->findCourseIdSlug($id);

        $this->authorize('update-course', $foundCourse);

        $foundCourse->delete();

        session()->flash('Course deleted');

        return redirect(route('courses.index'));
    }

    /**
     * Take course: update related CourseTake model
     * @param $id
     */
    public function takeCourse($id)
    {
        //find course and authorize action
        $foundCourse = $this->findCourseIdSlug($id);
        $this->authorize('access-course', $foundCourse);
        $this->authorize('take-course', $foundCourse);

        $user = Auth::user();

        /*
         * see if there is any course subscription for this course and user
         * if yes, return with error code
         * if no, take course and return with view
         * TODO: jQuery implementation
         */
        if($user->course->contains($foundCourse->id))
        {
            abort(405);
        }
        else
        {
            //take course
            $courseTake = new CourseTake();
            $courseTake->user_id = $user->id;
            $courseTake->course_id = $foundCourse->id;

            $this->show($id);
        }
    }
}
