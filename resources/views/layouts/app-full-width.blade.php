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

@yield('content')

@include('layouts.footer')
<livewire:scripts />
</body>
</html>
