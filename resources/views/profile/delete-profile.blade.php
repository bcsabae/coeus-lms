@extends('layouts.app')

@section('title', 'Delete profile')

@section('page-title')
    <h1>Üdv, {{Auth::user()->name}}!</h1>
@endsection

@section('content')
    <div class="h4 mt-4">Delete account</div>
    <p>If you delete your account, we delete all stored data from you. We will be unable to recover your data. Your subscriptions are cancelled, your learning progress is deleted. Delete your account only if you are absolutely sure!</p>
    <div class="container p-0 mb-5">
        @include('profile.partials.delete-profile')
    </div>
@endsection

@section('left-area')
    @include('profile.partials.menu')
@endsection
