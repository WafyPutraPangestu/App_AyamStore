@props(['title', 'buttonLink', 'buttonText'])

{{-- Tambahkan x-data untuk mengontrol animasi masuk --}}
<div x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 50) }" class="mb-8 border-b border-gray-200 pb-5">
    <div class="flex justify-between items-center">
        {{-- Animasikan judul --}}
        <div x-show="show"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Perbesar judul --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $title }}</h1>
            {{-- Perhalus garis bawah --}}
            <div class="h-0.5 w-24 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
        </div>

        {{-- Animasikan tombol (jika tampil) --}}
        @unless (request()->is('admin/manajemen') || request()->is('auth/register'))
        <div x-show="show"
             x-transition:enter="transition ease-out duration-500 delay-100" {{-- Tambahkan delay --}}
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">
            <a href="{{ $buttonLink }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg shadow-md
                      hover:from-amber-600 hover:to-orange-600 hover:shadow-lg
                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
                      transform hover:-translate-y-0.5 transition-all duration-300 ease-out"> {{-- Efek hover & transisi --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ $buttonText }}</span>
            </a>
        </div>
        @endunless
    </div>
</div>