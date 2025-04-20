<!-- resources/views/components/select.blade.php -->
@props(['name', 'label', 'options', 'selected' => null, 'placeholder' => 'Pilih salah satu'])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'rounded-xl bg-white/10 border border-black px-5 py-2 w-[300px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent']) }}
    >
        @if($placeholder)
            <option value="" disabled {{ !$selected ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $key => $value)
            <option 
                value="{{ $key }}" 
                {{ old($name, $selected) == $key ? 'selected' : '' }}
            >
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>