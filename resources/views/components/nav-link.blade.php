{{-- resources/views/components/nav-link.blade.php --}}
@props(['active'])

@php
// Base classes dengan efek hover yang lebih sederhana
$baseClasses = 'group relative inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300 ease-out rounded-md';

// Warna yang lebih cerah
$activeClasses = 'text-indigo-600 font-semibold'; 
$inactiveClasses = 'text-gray-600 hover:text-indigo-600';

$classes = $active
    ? "$baseClasses $activeClasses is-active"
    : "$baseClasses $inactiveClasses";

// Animasi garis bawah sederhana
$spanClasses = 'absolute bottom-[-2px] left-0 w-0 h-[2px] bg-indigo-500 transition-all duration-300 ease-out group-hover:w-full'; 
$activeSpanClasses = 'group-[.is-active]:w-full';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    {{-- Efek garis bawah --}}
    <span class="{{ $spanClasses }} {{ $activeSpanClasses }}"></span>
</a>