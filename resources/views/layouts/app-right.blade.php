<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>@yield('title')</title>
</head>
<body>
@include('layouts.nav2')
<div class="container-fluid">
    {{-- Dummy spacing --}}
    <div class="row m-5"></div>

    <div class="row">
        {{-- Center main area --}}
        <div class="col-sm-8 pl-5">
            @yield('content')
        </div>

        {{-- Right column area --}}
        <div class="col-sm-4">
            @yield('right-area')
        </div>

    </div>
</div>
@include('layouts.footer')
<livewire:scripts />
</body>
</html>
