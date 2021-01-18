<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    @stack('components')
</head>
<body class="bg-gray-100">
<div id="app" class="container-xl">
@yield('page')
</div>

<script src="{{ asset('/js/app.js') }}"></script>
@stack('scripts')
</body>
