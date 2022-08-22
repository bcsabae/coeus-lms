<div class="container my-4">
    <div class="row border-bottom mb-3"><h2>{{ $course->title }}</h2></div>

    {{-- Preview video --}}

    <div class="row">
        <div class="embed-responsive embed-responsive-21by9">
            <iframe class="embed-responsive-item" src="https://youtu.be/K4TOrB7at0Y" title="Preview video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>

    {{-- Category badges and metadata --}}
    <div class="row mt-2">
        <div class="col-sm-6">
            @include('courses.partials.categoryBadges')
        </div>
        <div class="col-sm-6 p-0 text-right text-muted">
            <i>
            Created at {{$course->created_at->toDateString()}}
                @if($course->updated_at != $course->created_at)
                    | modified at {{$course->updated_at->diffForHumans()}}
                @endif
            <br>
            {{
                (floor($course->length / 60)) > 0 ?
                (floor($course->length / 60)).'h '.($course->length % 60).'m' :
                ($course->length % 60).'m'
            }}
             | intermediate
            </i>
        </div>
    </div>

    {{-- Rating and subscriptions --}}
    <div class="row px-0 my-3">
        <div class="col-md-8 px-0 my-auto">
            <div class="d-flex">
                <div class="d-inline-flex">
                    @for($i = 0; $i<$course->rating; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>
                    @endfor
                </div>
                <div class="d-inline-flex text-muted"><i>|</i></div>
                <div class="d-inline-flex text-muted"><i>{{ $course->courseTake->count() }} subscriptions</i></div>
            </div>
        </div>
        <div class="col-md-4 text-right px-0 my-auto">
            @include('courses.partials.takeCourseButton')
        </div>

    </div>

    <div class="row mb-5">
        <h3>In this course</h3>
        {{$course->description}}
    </div>

    <div class="row">
        <div>
        @if($course->dependency->count())
            <h4>This course builds upon finishig the following ones</h4>
            @foreach($course->dependency as $dependency)
                @include('courses.partials.thumbnail-card', ['course'=>$dependency])
            @endforeach
        @else
            <p>This course can be finished without any prerequisites.</p>
        @endif
        </div>
    </div>

</div>
