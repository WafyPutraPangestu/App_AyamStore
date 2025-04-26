{{-- Ganti background gradient dengan warna solid yang lebih netral --}}
<div {{ $attributes->merge(['class' => 'p-4 border-b border-gray-200 bg-gray-50']) }}>
    <div class="relative w-full max-w-md"> {{-- Hapus animasi slideRight jika tidak diinginkan, atau pastikan definisinya ada --}}
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"> {{-- Posisi ikon lebih baik --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text"
               id="{{ $id ?? 'search' }}"
               placeholder="{{ $placeholder ?? 'Cari...' }}"
               {{-- Styling input lebih modern, fokus lebih lembut --}}
               class="pl-10 pr-4 py-2 w-full rounded-lg border-gray-300 shadow-sm
                      focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50 focus:ring-offset-0
                      transition duration-200 ease-in-out">
    </div>
</div>