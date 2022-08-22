@extends('layouts.app')

@section('content')
    <div class="container mb-7">
        <div class="col-md-3"></div>
        <div class="col-md-6 mx-auto">
            <h2 class="mb-5">Modify password</h2>
            @if(session('status'))
                <div class="alert alert-success">
                    <p>{{session('status')}}</p>
                </div>
            @endif
            <a class="btn btn-primary" href="{{route('courses.index')}}">Courses</a>
            <a class="btn btn-primary" href="{{route('profile.show')}}">Profile</a>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection()
