<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SYS CFDI</title>

    {{-- Template --}}
    @include('layouts.custom_links')
</head>
<body>
    <div id="app">
        {{-- Bootstrap default Login --}}
        {{-- @include('layouts.navbar')
        <main class="py-5">
            @yield('content')
        </main> --}}
        {{-- @if(!Auth::Check())
            Bootstrap default Login
            @include('layouts.navbar')
            <main class="py-5">
                @yield('content')
            </main>
        @else
            Template
            @include('layouts.main')
        @endif --}}
        @include('layouts.main')
    </div>
    @include('sweetalert::alert')
</body>
</html>
