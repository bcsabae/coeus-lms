Title:
<div><input type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}"></div>
@error('title')
<div>Please provide a title</div>
@enderror
Text:
<div><textarea name="content">{{ old('content', optional($post ?? null)->content) }}</textarea></div>
@if($errors->any)
    @foreach($errors->all() as $error)
        <div>Error: {{ $error }}</div>
    @endforeach
@endif
<div><input type="submit" value="Send"></div>
