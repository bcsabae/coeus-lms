 <div>
        <button class="btn btn-outline-primary btn-block" wire:click="takeCourseToggle">
            @if($isTaken)
                Untake course
            @else
                Take course
            @endif
        </button>
</div>
