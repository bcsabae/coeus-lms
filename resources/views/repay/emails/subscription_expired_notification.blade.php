<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$subscription->user->name}},</p>
<p>Your {{$subscription->type->name}} subscription has expired. The subscription was not renewed.</p>
<p>Please visit {{route('profile.billing')}} to update your subscription, or {{route('plans')}} to see the available plans!</p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
