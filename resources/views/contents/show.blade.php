@extends('layouts.app-right')

@section('title', $content->course->title . " - " . $content->title)

@section('content')
    <div class="container">
        <div class="row mb-4">
            <h1>{{ $content->title }}</h1>
        </div>
        <div class="row">
            {{$content->content}}
        </div>
    </div>
@endsection

@section('right-area')
    @include('contents.partials.list')
@endsection
