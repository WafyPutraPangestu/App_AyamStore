@props(['label', 'name'])

@php
    $defaults = [
        'type' => 'text',
        'id' => $name,
        'name' => $name,
        'class' => 'rounded-xl bg-white/10 border border-black px-5 py-2 w-[300px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
        'value' => old($name)
        
    ];
@endphp

<x-field :$label :$name>
    <input {{ $attributes($defaults) }}>
</x-field>