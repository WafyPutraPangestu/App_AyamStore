@props(['title', 'buttonLink', 'buttonText'])
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h1>
            <div class="h-1 w-20 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
        </div>
        @unless (request()->is('admin/manajemen') || request()->is('auth/register'))
        <a href="{{ $buttonLink }}" 
        class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg 
               hover:from-amber-600 hover:to-orange-600 transition-all duration-300 
               flex items-center gap-1 shadow-sm">
         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
         </svg>
         {{ $buttonText }}
     </a> 
    @endunless
    </div>
</div>