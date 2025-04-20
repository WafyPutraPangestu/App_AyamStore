<x-layout>
    <div class="">
        <h1>layout berhasil</h1>
        @auth
    <h1>{{ auth()->user()->name }}</h1>
    <h1>{{ auth()->user()->email }}</h1>
@endauth

    </div>
</x-layout>