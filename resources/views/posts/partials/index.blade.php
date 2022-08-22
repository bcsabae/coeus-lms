<div class="container my-4">
    <div class="row"><h2>{{ $post->title }}</h2></div>
    <div class="row"><p><i>Created by {{ $post->user->name }} {{ $post->created_at->diffForHumans() }}</i></p></div>
    <div class="row my-2"><p>{{ $post->content }}</p></div>

    {{-- Hide edit and delete buttons for now --}}
    {{-- @can('update-blogpost', $post) --}}
    <div class="row">
        <form action="{{ route('blog.destroy', ['blog' => $post->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger mx-1" value="Delete">
        </form>
        <form action="{{ route('blog.edit', ['blog' => $post->id]) }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-warning mx-1">Edit</button>
        </form>

    </div>
    {{-- @endcan --}}
    {{----}}
</div>
