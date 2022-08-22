@extends('layouts.app')

@section('title', 'Create course')

@section('content')

    {{-- Update route requires parameter to be passed: id --}}
    <form action="{{ route('courses.store') }}" method="post">
        @csrf
        {{-- update method needs to be spoofed to use PUT --}}
        @include('courses.partials.form')
    </form>

@endsection
