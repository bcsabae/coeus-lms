@extends('layouts.app')

@section('content')
    <div class="container mb-7">
        <div class="col-md-3"></div>
        <div class="col-md-6 mx-auto">
            <h3 class="mb-4">Confirm password</h3>
            <p class="mb-4 text-muted font-italic">You tried to access restricted parts of the platform. For your account's safety, we would like to make sure that it is you.</p>
            @include('auth.partials.login-form', [
                        'loginFormButton' => 'Confirm password',
                        'dontRenderRegistrationLink' => true,
                        'dontRenderRememberMe' => true
                    ])
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection()
