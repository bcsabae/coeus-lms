<div class="container">
    <form action="{{route('courses.index')}}" method="get" id="filterForm">
        <div class="h4 border-bottom pb-4">Találatok szűkítése</div>
        <div class="form-group border-bottom pb-4 pt-2">
            <label class="h5">Cetegory</label>
            @foreach($categories as $actCategory)
                <div class="form-check">
                    <input class="form-check-input" name="category[]" type="checkbox"
                           value="{{$actCategory->name}}"
                           id="{{$actCategory->name}}"
                           {{ in_array($actCategory->name, (is_array(request()->input('category')) ? request()->input('category', []) : [request()->input('category')]))
                                ? 'checked=true' : '' }}
                    >
                    <label class="form-check-label" for="{{$actCategory->name}}">
                        {{$actCategory->name}}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="form-group border-bottom pb-4 pt-2">
            <label class="h5">Length</label>
            <input type="range" class="form-control-range" name="length" min="0" max="180" step="30" id="filterDurationSlider"
                value="{{ request()->input('length', null) ? request()->input('length') : 0 }}"
            >
            {{-- This is managed by JS on load --}}
            <div id="filterDurationLabel"><div class="text-muted">any length</div></div>
        </div>
        <div class="form-group border-bottom pb-4 pt-2">
            <label class="h5">Rating</label>
            <input type="range" name="rating" min="0" max="5" id="filterRatingSlider"
                   value="{{ request()->input('rating', null) ? request()->input('rating') : 0 }}">
            {{-- This is managed by JS on load --}}
            <div id="filterRatingLabel"><div class="text-muted">any</div></div>
        </div>
        <div class="form-group border-bottom pb-4 pt-2">
            <label class="h5">Prerequisites</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="dependencies" value="none" id="neutralDependencyRadio"
                    {{ request()->input('dependencies', 'none') == 'none' ? 'checked=true' : '' }}
                >
                <label class="form-check-label" for="noDependencyRadio">
                    any
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="dependencies" value="0" id="noDependencyRadio"
                    {{ request()->input('dependencies', 'none') == '0' ? 'checked=true' : '' }}
                >
                <label class="form-check-label" for="noDependencyRadio">
                    without prerequisites
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="dependencies" value="1" id="dependencyRadio"
                    {{ request()->input('dependencies', 'none') == '1' ? 'checked=true' : '' }}
                >
                <label class="form-check-label" for="dependencyRadio">
                    with prerequisites
                </label>
            </div>
        </div>
        {{--<input type="hidden" name="orderBy" id="orderByHiddenValue">--}}
        <button type="submit" class="btn btn-secondary btn-block">Filter</button>
    </form>
    <div class="text-center mt-1">
        <a href="{{route('courses.index')}}" class="text-muted">Reset</a>
    </div>
</div>

<script src="{{asset('js/filter.js')}}" defer></script>
