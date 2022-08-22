@extends('layouts.app')

@section('title', 'Profile')

@section('page-title')
    <h1>Üdv, {{$user->name}}!</h1>
@endsection

@section('content')
    <div class="h4">Modify personal data</div>
    <div class="container p-0 mb-5">
        @include('profile.partials.profile-info')
    </div>
@endsection

@section('left-area')
    @include('profile.partials.menu')
@endsection
