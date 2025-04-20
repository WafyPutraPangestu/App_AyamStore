<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    @unless (request()->is('auth/login') || request()->is('auth/register'))
            <x-navigation />
    @endunless
    <main class="container mx-auto px-5 py-2.5">
        {{ $slot }}
    </main>
</body>
</html>