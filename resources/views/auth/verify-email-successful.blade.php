@extends('layouts.app')

@section('content')
    <p>Confirmation of {{$user->email}} was successful!</p>
    <a href="{{route('courses.index')}}">Take me to the courses!</a>
@endsection
