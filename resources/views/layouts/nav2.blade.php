<div class="containter">
<nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top shadow-sm">
    <a class="navbar-brand" href="{{ @route('home') }}">Logo.</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route('home') }}">Start page</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="{{ route('about.index') }}" data-toggle="dropdown" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    About
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a href="{{ route('about.index') }}" class="dropdown-item">Introduction</a></li>
                    <li><a class="dropdown-item" href="{{ route('about.faq') }}">FAQ</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route('courses.index') }}">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route('blog.index') }}">Blog</a>
            </li>
        </ul>

        {{-- Login/logout and Account --}}

        <ul class="nav navbar-nav ml-auto">
        @guest
            <a class="nav-link" aria-current="page" href="{{ route('login') }}">Login</a>
        @else
            <li class="nav-item dropleft">
                <a class="nav-link dropdown-toggle" href="{{ route('about.index') }}" data-toggle="dropdown" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a href="{{ route('profile.show') }}" class="dropdown-item">Account</a></li>
                    <li><a class="dropdown-item" href="{{ route('learning') }}">My courses</a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.billing') }}">Subscription</a></li>
                    <li>
                        <a class="dropdown-item" aria-current="page" href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        >Log out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        @endguest
        </ul>
    </div>
</nav>
</div>
