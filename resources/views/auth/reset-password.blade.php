@extends('layouts.app')

@section('content')
    <div class="container mb-7">
        <div class="col-md-3"></div>
        <div class="col-md-6 mx-auto">
            <h3 class="mb-4">Set new password</h3>
            @include('auth.partials.register-form', [
                        'registerFormDontRenderName' => true,
                        'registerFormHandlingRoute' => route('password.update'),
                        'registerFormToken' => $token
                        ])
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection()
