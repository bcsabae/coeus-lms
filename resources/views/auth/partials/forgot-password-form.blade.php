<form class="mt-3" method="POST" action="{{ route('password.request') }}">
    @csrf

    <div class="form-group">
        <label>E-mail</label>
        <input name="email" value="{{ old('email') }}" required class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}">
        @if($errors->has('email'))
            <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-block">{{ 'Send reset email' }}</button>
</form>
