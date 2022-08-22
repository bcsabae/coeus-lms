@extends('layouts.app-right')

@section('title', 'Course')

@section('content')
    <div class="container">
            @include('courses.partials.index')
    </div>
@endsection

@section('right-area')
    <div class="container mt-6">
        <div class="mb-4">
            @include('courses.partials.takeCourseButton')
        </div>

        @include('contents.partials.list')
    </div>
@endsection
