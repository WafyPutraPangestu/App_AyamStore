<!DOCTYPE html>
{{-- Mengatur bahasa HTML sesuai locale aplikasi Laravel --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul Halaman - Lebih baik dinamis per halaman jika memungkinkan --}}
    <title>{{ $title ?? 'PT Ayam Sejahtera Modern' }}</title> {{-- Contoh penggunaan variabel $title atau default --}}

    {{-- Script Eksternal --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Alpine.js bisa juga dibundel via Vite jika diinginkan untuk optimasi --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Vite - Memuat aset CSS dan JS utama --}}
    {{-- Pastikan resources/css/app.css mengimpor Tailwind CSS --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- (Saran) Pertimbangkan menghapus custom scrollbar --}}
    {{-- Scrollbar bawaan OS lebih konsisten dan aksesibel --}}
    {{-- <style>
        /* Style scrollbar (opsional, untuk estetika) */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c0c0c0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }
    </style> --}}

    {{-- (Saran) Tambahkan Favicon --}}
    {{-- <link rel="icon" href="/favicon.ico" sizes="any"> --}}
    {{-- <link rel="icon" href="/favicon.svg" type="image/svg+xml"> --}}
    {{-- <link rel="apple-touch-icon" href="/apple-touch-icon.png"> --}}

</head>
{{-- Body: Gunakan bg-slate-50 agar konsisten dengan gaya modern, pastikan font-sans diatur di tailwind.config.js --}}
<body class="bg-slate-50 font-sans antialiased">
    {{--
        Konfigurasi Font:
        Pastikan Anda telah mengatur font-family yang modern di `tailwind.config.js`.
        Contoh:
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'ui-sans-serif', 'system-ui', ...], // Ganti 'Inter' dengan font pilihan Anda
                },
            },
        },
    --}}

    {{-- Navigasi: Tampilkan kecuali di halaman login, register, atau form order khusus --}}
    {{-- Pastikan komponen <x-navigation> sudah di-styling modern --}}
    @unless (request()->is('auth/login') || request()->is('auth/register') || request()->is('user/order-form'))
        <x-navigation />
    @endunless

    {{-- Konten Utama Halaman --}}
    {{-- Diberi class flex-grow agar footer menempel di bawah jika konten pendek (membutuhkan flex di body/wrapper) --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>
</body>
</html>