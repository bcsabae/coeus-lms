@extends('layouts.app')

@section('title', 'Edit post')

@section('content')

    {{-- Update route requires parameter to be passed: id --}}
    <form action="{{ route('blog.update', ['blog' => $post->id]) }}" method="post">
        @csrf
        {{-- update method needs to be spoofed to use PUT --}}
        @method('PUT')
        @include('posts.partials.form')
    </form>

@endsection
