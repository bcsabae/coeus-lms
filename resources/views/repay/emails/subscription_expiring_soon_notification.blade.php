<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$subscription->user->name}},</p>
<p>Your {{$subscription->type->name}} subscription is going to expire on {{$subscription->end}}.</p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
