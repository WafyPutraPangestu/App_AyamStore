<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'PT Ayam Sejahtera Modern' }}</title>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-50 font-sans antialiased">
    @unless (request()->is('auth/login') || request()->is('auth/register') || request()->is('admin/detailOrder') || request()->is('user/order-form*') ||request()->is('admin/detailKurir*') || request()->is('user/pembayaran*')) 
        <x-navigation /> 
    @endunless

    <main class="flex-grow">    
        {{ $slot }}
    </main>
</body>
</html>
