@extends('layouts.app')

@section('title', 'Courses')

@section('page-title')
    <div class="mb-5"><h1>Courses</h1></div>
@endsection

@section('left-area')
    @include('courses.partials.filter')
@endsection

@section('content')
    <div class="container mb-7">

        <div class="row">
            @include('courses.partials.order')
        </div>

        {{ $courses->links() }}

        @php($cardsPerRow = 3)
        @for($i = 0; $i<count($courses); $i+=$cardsPerRow)
            <div class="card-deck">
            @for($j=0; $j<$cardsPerRow; $j++)
                @if($courses[$i+$j])
                    @include('courses.partials.thumbnail-card', ['course' => $courses[$i+$j]])
                @else
                    <div class="card border-0 mx-2 my-2"></div>
                @endif
            @endfor
            </div>
        @endfor
        @if(count($courses) == 0)
            <div class="text-muted">
                No items to display.
            </div>
        @endif

        {{ $courses->links() }}
    </div>
@endsection
