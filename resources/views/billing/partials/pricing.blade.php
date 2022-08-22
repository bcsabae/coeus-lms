<div class="container-fluid bg-white align-items-center my-7 no-gutters">
    <div class="text-center text-dark w-50 mx-auto">
        <h3 class="display-4">Prices</h3>
        <p class="lead mt-4 mb-5">Whether you'd like to just look around, or need full access, we have a deal for you. If you have any questions, feel free to reach out to us!</p>
    </div>
    <div class="card-deck text-center px-7">
        <div class="card mx-4">
            <div class="card-header">
                <h4>Guest</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title">
                    Free
                    <small class="text-muted">/ month</small>
                </h1>
                <ul class="list-unstyled">
                    <li>Blog, newsletter</li>
                    <li>Partial access to introductory courses</li>
                    <br>
                </ul>
                @include('billing.partials.pricingCTA', ['thisPlan' => 'guest'])
            </div>
        </div>
        <div class="card mx-4">
            <div class="card-header">
                <h4>Member</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title">
                    10 USD
                    <small class="text-muted">/ month</small>
                </h1>
                <ul class="list-unstyled">
                    <li>Blog, newsletter</li>
                    <li>Full access to all courses</li>
                    <br>
                    <br>
                </ul>
                @include('billing.partials.pricingCTA', ['thisPlan' => 'member'])
            </div>
        </div>
        <div class="card mx-4">
            <div class="card-header">
                <h4>VIP member</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title">
                    20 USD
                    <small class="text-muted">/ month</small>
                </h1>
                <ul class="list-unstyled">
                    <li>Blog, newsletter</li>
                    <li>Full access to all courses</li>
                    <li>Premium member support</li>
                    <br>
                </ul>
                @include('billing.partials.pricingCTA', ['thisPlan' => 'support'])
            </div>
        </div>
    </div>
</div>
