@extends('layouts.app')

@section('title', 'Change password')

@section('page-title')
    <h1></h1>
@endsection

@section('content')
    <div class="h4 text-center mb-5">Change password</div>
    <div class="container p-0 mb-5 text-center">
        @include('profile.partials.change-password-form')
    </div>
@endsection
