@props(['active'])

@php
$baseClasses = 'group inline-flex items-center relative px-5 py-2 text-sm font-medium transition-all duration-300 ease-in-out overflow-hidden';

$activeClasses = 'text-blue-600 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-es-xl [&>span]:w-full';
$inactiveClasses = 'text-gray-500 hover:text-blue-600 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 rounded-lg';

$classes = $active 
    ? "$baseClasses $activeClasses" 
    : "$baseClasses $inactiveClasses";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-indigo-700 transition-all duration-300 group-hover:w-full"></span>
</a>