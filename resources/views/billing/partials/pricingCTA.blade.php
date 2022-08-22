<a class="btn btn-lg btn-block btn-outline-primary {{$activePlan == $thisPlan ? 'disabled' : ''}}"
   href="
   @if($activePlan)
       {{$redirectTo}}
   @else
       {{route('register')}}
   @endif
    ">
    @if($activePlan == $thisPlan)
        Active subscription
    @elseif($activePlan)
        Subscription
    @else
        Registration
    @endif
</a>
