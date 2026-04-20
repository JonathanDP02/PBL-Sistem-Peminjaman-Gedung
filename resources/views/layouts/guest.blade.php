<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="font-sans text-gray-900 antialiased bg-gray-950">
        <div class="min-h-screen flex flex-col justify-center items-center relative">
            <!-- Header Navigation -->
            <div class="absolute top-0 left-0 right-0 px-8 py-6 flex justify-between items-center">
                <a href="/">
                    <x-application-logo class="h-8 w-auto" />
                </a>
                <div class="text-sm text-gray-300">Enterprise Booking System</div>
            </div>

            <!-- Main Content -->
            <div class="w-full max-w-md px-6 py-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="absolute bottom-0 left-0 right-0 px-8 py-6 text-center">
                <div class="text-xs text-gray-500 mb-4">© 2024 SPACE.IN ENTERPRISE • ACADEMIC PORTAL</div>
                
                <!-- Decorative Dots -->
                <div class="flex justify-center gap-2 mb-4">
                    <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                </div>
                
                <div class="text-xs text-gray-500">🔒 SECURED BY IBM</div>
            </div>
        </div>
    </body>
</html>
