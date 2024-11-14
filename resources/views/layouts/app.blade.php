<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="vite:mode" content="{{ config('app.debug') ? 'development' : 'production' }}">
        <meta name="vite:manifest" content="{{ asset('manifest.json') }}">
        <meta name="vite:modulepreload" content="{{ asset('resources/js/app.js') }}">
        <meta name="vite:modulepreload" content="{{ asset('resources/css/app.css') }}">
        <meta name="vite:preload" content="{{ asset('resources/js/app.js') }}">
        <meta name="vite:preload" content="{{ asset('resources/css/app.css') }}">
        <script src="https://cdn.tailwindcss.com/"></script>

        <title>{{ config('app.name', 'Komunitani') }}</title>
        <link rel="icon" href="https://i.ibb.co.com/89qxHLW/logokomunitani-chara.png" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
