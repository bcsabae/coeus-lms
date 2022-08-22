{{-- parameters:
        $loginFormActionRoute: route for the form to use. If not set, default login route will be used
        $loginFormEmail: if set, e-mail will be pre-filled for the user
        $loginFormButton: button text for send button. If not set, defaults to 'login'
        $dontRenderRegistrationLink: if set, registration section will NOT be rendered under the form
        $dontRenderRememberMe: if set, remember me section will NOT be rendered
        --}}
<form class="mt-3" method="POST" action="{{ $loginFormActionRoute ?? route('login') }}">
    @csrf

    <div class="form-group">
        <label>E-mail</label>
        <input name="email" value="{{ $loginFormEmail ??  old('email') }}" required class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}">
        @if($errors->has('email'))
            <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
        @endif
    </div>

    <div class="form-group">
        <label>Jelszó</label>
        <input type="password" name="password" required class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">

        @if($errors->has('password'))
            <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
        @endif
    </div>

    @if(!isset($dontRenderRememberMe))
    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="remember" value="{{ old('remember') ? 'checked' : '' }}">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
    </div>
    @endif

    <button type="submit" class="btn btn-primary btn-block">{{ $loginFormButton ?? 'Login' }}</button>

    @if(!isset($dontRenderRegistrationLink))
    <div class="text-center my-3">
        <a class="mx-auto" href="{{route('register')}}">No account yet? Register here!</a>
    </div>
    @endif

</form>
