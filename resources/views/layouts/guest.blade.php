<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
            <div class="mb-6 pt-2 text-center">
                <a href="/" class="logo-container">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="mx-auto h-24 w-auto object-contain" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg border-0">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
