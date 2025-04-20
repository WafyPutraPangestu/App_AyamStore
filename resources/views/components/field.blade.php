@props(['label', 'name'])

<div>
    @if ($label)
        <x-label :$name :$label />
    @endif

    <div class="mt-1">
        {{ $slot }}

        <x-error :error="$errors->first($name)" />
    </div>
</div>