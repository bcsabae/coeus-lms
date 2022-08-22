@extends('layouts.app')

@section('title', 'Blog post')

@section('content')
    <div class="container">
        <div class="row">
            @include('posts.partials.index')
        </div>
    </div>
@endsection
