@extends('layouts.app')

@section('content')
    <div class="container mb-7">
        <div class="col-md-3"></div>
        <div class="col-md-6 mx-auto">
            <h3 class="mb-5">Login</h3>
            @include('auth.partials.login-form')
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection()
