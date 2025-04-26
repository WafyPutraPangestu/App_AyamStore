<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT Ayam Sejahtera</title> {{-- Ganti dengan nama toko --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Vite --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Tambahkan gaya dasar jika perlu --}}
    <style>
        /* Style scrollbar (opsional, untuk estetika) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c0c0c0; /* Warna abu-abu lembut */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0; /* Sedikit lebih gelap saat hover */
        }
    </style>
</head>
{{-- Tambahkan background abu-abu sangat muda ke body --}}
<body class="bg-gray-50 font-sans antialiased">
    @unless (request()->is('auth/login') || request()->is('user/order-form') || request()->is('auth/register'))
        {{-- Pastikan komponen navigasi Anda juga di-styling --}}
        <x-navigation />
    @endunless

    {{-- Beri padding vertikal lebih banyak pada main content --}}
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Mungkin tambahkan Footer di sini --}}
    {{-- <x-footer /> --}}
</body>
</html>