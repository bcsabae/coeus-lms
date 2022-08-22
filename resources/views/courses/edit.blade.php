@extends('layouts.app')

@section('title', 'Edit course')

@section('content')

    {{-- Update route requires parameter to be passed: id --}}
    <form action="{{ route('courses.update', ['course' => $course->id]) }}" method="post">
        @csrf
        {{-- update method needs to be spoofed to use PUT --}}
        @method('PUT')
        @include('courses.partials.form')
    </form>

@endsection
