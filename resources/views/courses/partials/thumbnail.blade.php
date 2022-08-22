<div class="container my-4">
    <div class="row border-bottom mb-3"><h2>
        {{-- Only render link if the user is subscribed --}}
        @can('access-course', $course)
            <a href="{{ route('courses.show', ['course' => $course->slug]) }}">{{ $course->title }}</a>
        @else
            {{ $course->title }}
        @endcan
    </h2></div>
    {{--
    <div class="row"><p><i>Created at {{ $course->created_at->diffForHumans() }}</i></p></div>
    <div class="row"><p><i>Rated {{ $course->rating }} from 5</i></p></div>
    --}}

    {{-- Category badges --}}
    @include('courses.partials.categoryBadges')

    <div class="row">
        Accessible by {{$course->accessRight->name}}
    </div>
    <div class="row my-2"><p>{{ $course->description }}</p></div>
    {{-- Take this out for now
    <div class="row">
        <form action="{{ route('courses.destroy', ['course' => $course->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger mx-1" value="Delete">
        </form>
        <form action="{{ route('courses.edit', ['course' => $course->id]) }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-warning mx-1">Edit</button>
        </form>
    </div>
    --}}
</div>
