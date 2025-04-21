@props(['active'])

@php
$baseClasses = 'group inline-flex items-center relative text-[13px] font-medium transition-all duration-300 ease-in-out';

$activeClasses = 'text-blue-600 ';
$inactiveClasses = 'text-gray-600  hover:text-blue-600 ';

$classes = $active 
    ? "$baseClasses $activeClasses" 
    : "$baseClasses $inactiveClasses";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <div class="ml-auto">
        @if($active)
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif
    </div>
</a>