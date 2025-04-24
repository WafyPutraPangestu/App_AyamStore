@props(['active'])

@php
// Sedikit penyesuaian warna dan efek
$baseClasses = 'group inline-flex items-center relative px-4 py-2 text-sm font-medium transition-all duration-300 ease-in-out overflow-hidden rounded-md'; // px lebih kecil, rounded lebih halus

// Warna Biru Teal/Indigo yang elegan
$activeClasses = 'text-indigo-700 bg-indigo-50'; // Warna aktif lebih kontras
$inactiveClasses = 'text-gray-600 hover:text-indigo-700 hover:bg-indigo-50'; // Hover lebih jelas

$classes = $active
    ? "$baseClasses $activeClasses"
    : "$baseClasses $inactiveClasses";

// Animasi garis bawah sedikit diubah agar lebih halus
$spanClasses = 'absolute bottom-0 left-0 w-0 h-[2px] bg-indigo-600 transition-all duration-300 group-hover:w-full'; // Tinggi 2px, warna konsisten

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <span class="{{ $spanClasses }}"></span>
</a>