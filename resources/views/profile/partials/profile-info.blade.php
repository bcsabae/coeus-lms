<form method="POST" action="{{route('profile.update')}}">
    @csrf
    <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" required name="name"
               value="{{$errors->has('name') ? old('name') : $user->name}}">
        @if($errors->has('name'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group">
        <label>E-mail</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" required name="email"
               value="{{old('email') ? old('email') : $user->email}}">
        @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <button type="submit" class="btn btn-secondary">Update info</button>
</form>
