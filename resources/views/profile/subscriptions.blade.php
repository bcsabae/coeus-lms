@extends('layouts.app')

@section('title', 'Subscriptions')

@section('page-title')
    <h1>Üdv, {{$user->name}}!</h1>
@endsection

@section('content')
    <div class="h4">Subscriptions</div>
    <div class="container p-0 mb-5">
        @foreach($subscriptions as $subscription)
            @include('profile.partials.subscription', ['subscription' => $subscription])
        @endforeach
    </div>
@endsection

@section('left-area')
    @include('profile.partials.menu')
@endsection
