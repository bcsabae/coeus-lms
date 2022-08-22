<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$subscription->user->name}},</p>
<p>Your {{$subscription->type->name}} subscription's trial period is over. We will bill you and go on with the subscription.</p>
<p>To manage your subscriptions, visit {{route('profile.plans')}}</p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
