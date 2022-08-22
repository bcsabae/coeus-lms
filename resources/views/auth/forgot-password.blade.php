@extends('layouts.app')

@section('content')
    <div class="container mb-7">
        <div class="col-md-3"></div>
        <div class="col-md-6 mx-auto">
            <h3 class="mb-4">Jelszó visszaállítása</h3>
            <p class="mb-4 text-muted font-italic">We'll send you an e-mail with instructions on how to reset your password.</p>
            @include('auth.partials.forgot-password-form')
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection()
