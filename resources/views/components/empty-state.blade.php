<!-- resources/views/components/empty-state.blade.php -->
@props(['title' => 'No Data Available', 'message' => 'Please add some data to get started.'])
<div class="px-4 py-12 text-center text-gray-500">
    <div class="flex flex-col items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-lg font-medium">{{ $title }}</span>
        <p class="text-sm text-gray-400 mt-1">{{ $message }}</p>
    </div>
</div>