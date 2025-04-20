<!-- resources/views/components/data-table.blade.php -->
<div class="overflow-x-auto min-h-[400px]">
    <table class="table-auto w-full">
        <thead>
            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 text-sm">
                {{ $thead }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>