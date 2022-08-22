<div class="accordion" id="contentsAccordion">
    <div class="list-group" id="course-content-list">
    @foreach($course->content as $contentElement)
        <a href="{{ route('content.show', ['content' => $contentElement->slug, 'course'=>$course->slug]) }}"
           class="list-group-item list-group-item-action
        @if(auth()->user() ?
                (auth()->user()->course->contains($course->id)) :
                false)
            @if(count($contentElement->finishedContent->where('user_id', auth()->user()->id)))
               font-weight-bold
            @else
               font-weight-light
            @endif

            @isset($content)
               @if($contentElement->id == $content->id)
                    active
               @endif
            @endisset
            ">
        @else
            disabled">
        @endif
            <b>{{ $contentElement->number }}. </b>
            {{ $contentElement->title }}
        </a>
    @endforeach
    </div>
</div>
