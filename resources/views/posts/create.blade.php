@extends('layouts.app')

@section('title', 'New post')

@section('content')

    <form action="{{ route('blog.store') }}" method="post">
        @csrf
        @include('posts.partials.form')
    </form>

@endsection
