@props(['editRoute', 'deleteRoute', 'confirmMessage' => 'Apakah Anda yakin ingin menghapus item ini?'])
<div class="flex justify-center gap-2">
    <!-- Tombol Edit -->
    <a href="{{ $editRoute }}" 
       class="p-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all duration-300"
       title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </a>
    
    <!-- Tombol Delete -->
    <form action="{{ $deleteRoute }}" method="POST" data-message="{{ $confirmMessage }}" class="delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="p-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-all duration-300"
                title="Hapus">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </form>
</div>
