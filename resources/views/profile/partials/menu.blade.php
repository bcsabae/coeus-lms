<div class="accordion">
    <div class="list-group">
        <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action">
            Personal data
        </a>
        <a href="{{ route('profile.subscriptions') }}" class="list-group-item list-group-item-action">
            Subscriptions
        </a>
        <a href="{{ route('profile.billing') }}" class="list-group-item list-group-item-action">
            Billing info
        </a>
        <a href="{{ route('profile.password.view') }}" class="list-group-item list-group-item-action">
            Modify password
        </a>
        <a href="{{ route('profile.delete.view') }}" class="list-group-item list-group-item-action">
            Delete account
        </a>
    </div>
</div>
