<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — {{ config('app.name') }}</title>
    @vite(['resources/css/admin.css'])
</head>
<body class="antialiased border-top-wide border-primary d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
                <h1 class="h2">{{ config('app.name') }}</h1>
            </a>
        </div>

        @include('layouts.partials.alerts')

        @yield('content')
    </div>
</div>
</body>
</html>
