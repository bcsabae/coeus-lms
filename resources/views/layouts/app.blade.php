<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('css/custom.css') }}">--}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>@yield('title')</title>
</head>
<body>
    @include('layouts.nav2')
    <div class="container-fluid">
        {{-- Dummy spacing --}}
        <div class="row m-5"></div>

        {{-- Top row --}}
        <div class="row mb-5">

            {{-- Top left area --}}
            <div class="col-sm-2">
            </div>

            {{-- Top middle area --}}
            <div class="col-sm-8">
                @yield('page-title')
            </div>

            {{-- Top right area --}}
            <div class="col-sm-2">
            </div>
        </div>

        {{-- Body row --}}
        <div class="row">

            {{-- Left column area --}}
            <div class="col-sm-2">
                @yield('left-area')
            </div>

            {{-- Center main area --}}
            <div class="col-sm-8">
                @yield('content')
            </div>

            {{-- Right column area --}}
            <div class="col-sm-2">
                @yield('right-area')
            </div>

        </div>
    </div>

    @include('layouts.footer')
    <livewire:scripts />
</body>
</html>
