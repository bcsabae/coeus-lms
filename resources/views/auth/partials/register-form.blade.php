{{-- Parameters:
     $registerFormHandlingRoute: route to handle request, defaults to 'register' route
     $registerFormDontRenderName: if set, no name field will be rendered
     $registerFormToken: if set, a hidden token will be also included (for password reset), and labels will be adjusted for password reset
     //NOT NOW!! $registerFormEmail: if set, the email field will be filled with this value and will be disabled
--}}

<form method="POST" action="{{ $registerFormHandlingRoute ?? route('register') }}">
    @csrf
    @if(!isset($registerFormDontRenderName))
    <div class="form-group">
        <label>Name</label>
        <input name="name" value="{{ old('name') }}" required class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
        @if($errors->has('name'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
    @endif

    <div class="form-group">
        <label>E-mail</label>
        <input name="email" value="{{old('email') }}" required class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}">
        @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label>{{ isset($registerFormToken) ? "New password" : "Password"}}</label>
        <input type="password" name="password" required class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">

        @if($errors->has('password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label>{{isset($registerFormToken) ? "Confirm new password" : "Confirm password"}}</label>
        <input type="password" name="password_confirmation" required class="form-control">
    </div>

    @isset($registerFormToken)
        <input type="hidden" name="token" value="{{$registerFormToken}}">
    @endisset

    <button type="submit" class="btn btn-primary btn-block">{{isset($registerFormToken) ? "Set new password" : 'Register' }}</button>

</form>
