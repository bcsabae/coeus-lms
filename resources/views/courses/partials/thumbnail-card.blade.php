<div class="card d-flex flex-column align-items-start mx-2 my-2">
    <img class="card-img-top border" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Aspect-ratio-16x9.svg/1024px-Aspect-ratio-16x9.svg.png">
    <div class="card-body d-flex align-items-start flex-column">
        <div class="card-title d-flex h4 mb-0">
            {{$course->title}}
        </div>
        <div class="container">
            @include('courses.partials.categoryBadges')
        </div>

        <div class="card-text d-flex mt-auto py-2 col">
            <i>
                {{
                    (floor($course->length / 60)) > 0 ?
                    (floor($course->length / 60)).'ó '.($course->length % 60).'p' :
                    ($course->length % 60).'p'
                }}
            </i>
            <br>
            Here will be the description of the course. This text is for testing only.
        </div>
        <a class="btn btn-block btn-outline-primary mt-4" href="{{ route('courses.show', ['course' => $course->slug]) }}">
            Details
        </a>
    </div>
</div>
