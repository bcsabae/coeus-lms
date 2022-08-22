<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$payment->user->name}},</p>
<p>We were unable to finish your payment for your {{$payment->subscription->type->name}} subscription.</p>
<p>You should have paid {{$payment->price}} USD.</p>
<p>Your transaction ID is {{$payment->id}}.</p>
<p>We will retry this payment on {{$payment->paymentIntent->next_attempt}}</p>
<p>You can cancel your subscription under your profile.</p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
