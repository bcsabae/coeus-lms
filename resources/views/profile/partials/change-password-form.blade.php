<form method="post" action="{{route('profile.change-password')}}" class="w-50 mx-auto">
    @csrf
    <div class="form-group">
        <label>Current password</label>
        <input type="password" class="form-control @error('current_password') is-invalid @enderror" required name="current_password">
        @if($errors->has('current_password'))
            <span class="invalid-feedback">
                <strong>Wrong password</strong>
            </span>
        @endif
    </div>
    <div class="form-group">
        <label>New password</label>
        <input type="password" class="form-control @error('new_password') is-invalid @enderror" required name="new_password">
        @if($errors->has('new_password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('new_password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label>Confirm new password</label>
        <input type="password" class="form-control" required name="new_password_confirmation">
    </div>

    <button type="submit" class="btn btn-secondary">Set new password</button>
</form>
