{{-- Take course button --}}

@can('access-course', $course)
    @can('take-course', $course)
        @if(Auth::user()->hasVerifiedEmail())
            @livewire('take-course', ['courseId' => $course->id])
        @else
            <div>
                <a class="btn btn-outline-primary btn-block btn-in"
                   href="{{route('verification.notice')}}">
                    Take course
                </a>
            </div>
        @endif
    @else
        <div>
            <a class="btn btn-outline-primary btn-block btn-in"
                href="{{route('plans')}}">
                Take course
            </a>
        </div>
    @endcan
@else
    <div>
        <button class="btn btn-outline-primary btn-block btn-in disabled">
            Log in to take course!
        </button>
    </div>
@endcan

