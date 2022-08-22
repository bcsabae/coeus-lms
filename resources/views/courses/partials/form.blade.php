Title:
<div><input type="text" name="title" value="{{ old('title', optional($course ?? null)->title) }}"></div>
Description:
<div><textarea name="description">{{ old('description', optional($course ?? null)->description) }}</textarea></div>
Rating:
<div><input type="number" name="rating"  value="{{ old('rating', optional($course ?? 5)->rating) }}"></div>
Access right:
<div>
    <select name="access_right_id">
        @foreach($rights as $right)
            <option value="{{ $right['id'] }}">{{ $right['name'] }}</option>
        @endforeach
    </select>
</div>


@if($errors->any)
    @foreach($errors->all() as $error)
        <div>Error: {{ $error }}</div>
    @endforeach
@endif
<div><input type="submit" value="Send"></div>
