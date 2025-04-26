{{-- resources/views/components/nav-link.blade.php (Revisi dari Gaya Pertama) --}}
@props(['active'])

@php
// Gaya pertama (underline tengah) + Peningkatan
$baseClasses = 'group relative inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 ease-out overflow-visible rounded-md transform hover:-translate-y-0.5 hover:scale-[1.03]'; // Tambah transform hover

// Warna sedikit disesuaikan
$activeClasses = 'text-indigo-600 font-semibold'; // Aktif
$inactiveClasses = 'text-gray-500 hover:text-indigo-600'; // Hover lebih jelas

$classes = $active
    ? "$baseClasses $activeClasses is-active"
    : "$baseClasses $inactiveClasses";

// Animasi garis bawah: Muncul dari tengah, lebih tebal, GRADIENT, warna solid saat aktif
$spanClasses = 'absolute bottom-[-2px] left-1/2 -translate-x-1/2 w-0 h-[3px] bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-300 ease-out group-hover:w-[calc(100%-1rem)] rounded-full'; // Gradient, lebih tebal, agak ke bawah, rounded
$activeSpanClasses = 'group-[.is-active]:w-[calc(100%-1rem)] group-[.is-active]:bg-gradient-to-r group-[.is-active]:from-indigo-600 group-[.is-active]:to-indigo-600'; // Aktif: Warna solid (gradient dihapus)

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    {{-- Gabungkan class span dasar dan aktif --}}
    <span class="{{ $spanClasses }} {{ $activeSpanClasses }}"></span>
</a>