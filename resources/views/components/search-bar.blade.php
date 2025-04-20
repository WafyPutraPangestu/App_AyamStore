<!-- resources/views/components/search-bar.blade.php -->
<div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 border-b border-gray-200">
    <div class="relative w-full max-w-md animate-slideRight">
        <input type="text" 
               id="{{ $id ?? 'search' }}" 
               placeholder="{{ $placeholder ?? 'Cari...' }}" 
               class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-300 
                      focus:ring-2 focus:ring-amber-500 focus:border-transparent 
                      transition-all duration-300">
        <div class="absolute left-3 top-2.5 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
</div>