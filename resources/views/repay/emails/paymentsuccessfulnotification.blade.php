<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$payment->user->name}},</p>
<p>You have successfully paid for your {{$payment->subscription->type->name}} subscription.</p>
<p>You have paid {{$payment->price}} USD.</p>
<p>Your transaction ID is {{$payment->id}}.</p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
