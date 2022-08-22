<div class="row">
    @foreach($course->category as $actCategory)
        <p><a href="{{ route('courses.index', ['category'=>$actCategory->name]) }}"><span class="badge badge-info mr-1">{{ $actCategory->name }}</span></a></p>
    @endforeach
</div>
