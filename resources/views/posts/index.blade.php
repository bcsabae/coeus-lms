@extends('layouts.app')

@section('title', 'Blog')

@section('page-title')
    <div class="mb-5"><h1>Blog</h1></div>
@endsection

@section('content')

    <div class="container">
        {{ $posts->links() }}

        @foreach($posts as $post)
            <div class="row">
                @include('posts.partials.thumbnail')
            </div>
        @endforeach
    </div>

    {{ $posts->links() }}
@endsection
