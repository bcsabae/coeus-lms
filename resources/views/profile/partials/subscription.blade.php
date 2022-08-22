<div class="border bg-secondary mb-5">
    <p>{{$subscription->type->name}}</p>
    <p>State: {{$subscription->status == 'active' ? 'active' : 'inactive'}}</p>
    <p>Expires: {{$subscription->end}}</p>
</div>
