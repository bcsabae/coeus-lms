@extends('layouts.app')

@section('content')
    @if(session('message'))
        <p>{{session('message')}}</p>
    @endif
    <p>Please verify your e-mail address before starting to use the platform.</p>
    <p>We've sent a confirmation link to {{auth()->user()->email}}.</p>
    <form method="post" action="{{route('verification.send')}}">
        @csrf
        <div class="text-muted"><p>Didn't get your link?</p></div>
        <input type="submit" value="Send link again">
    </form>
@endsection
