<x-layout>
  <div class="container mx-auto px-4 py-6">
    <!-- Header dengan styling gradient -->
    <div 
      x-data="{ show: false }" 
      x-init="() => { setTimeout(() => show = true, 50) }" 
      class="mb-8 border-b border-gray-200 pb-5"
    >
      <div class="flex justify-between items-center">
        <div 
          x-show="show"
          x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 translate-y-2"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          <h1 class="text-3xl font-bold text-gray-800 mb-1">Edit Produk</h1>
          <div class="h-0.5 w-24 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
        </div>
      </div>
    </div>

    <!-- Form dengan styling yang konsisten -->
    <div class="bg-white rounded-lg shadow-md p-6 animate-fadeIn">
      <x-form method="POST" action="{{ route('admin.update', $produk->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-6">
          <x-input 
            name="nama_produk" 
            label="Nama Produk" 
            type="text" 
            placeholder="Masukkan nama produk" 
            :value="$produk->nama_produk" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300"
          />
        </div>

        <div class="mb-6">
          <x-input 
            name="harga" 
            label="Harga" 
            type="text" 
            placeholder="Masukkan harga produk" 
            :value="$produk->harga" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300"
          />
        </div>

        <div class="mb-6">
          <x-input 
            name="stok" 
            label="Stok" 
            type="number" 
            placeholder="Masukkan jumlah stok" 
            :value="$produk->stok" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300"
          />
        </div>

        <div class="mb-6">
          <x-input 
            name="deskripsi" 
            label="Deskripsi" 
            type="textarea" 
            placeholder="Masukkan deskripsi produk" 
            :value="$produk->deskripsi" 
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300"
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 mb-2">Foto Produk Saat Ini</label>
          <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
            @if ($produk->gambar)
              <img src="{{ asset('storage/images/' . $produk->gambar) }}" class="h-36 rounded object-cover mx-auto" alt="Gambar produk lama">
            @else
              <div class="flex items-center justify-center h-36 bg-gray-100 rounded">
                <p class="text-gray-400">Belum ada gambar</p>
              </div>
            @endif
          </div>
        </div>

        <div class="mb-8">
          <x-input 
            name="gambar" 
            label="Ganti Foto Produk" 
            type="file" 
            placeholder="Pilih file gambar baru (opsional)"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300"
          />
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.dataProduk') }}" class="mr-4 px-5 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300">
            Batal
          </a>
          <button 
            type="submit" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg shadow-md 
                  hover:from-amber-600 hover:to-orange-600 hover:shadow-lg 
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 
                  transform hover:-translate-y-0.5 transition-all duration-300 ease-out"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>Update Produk</span>
          </button>
        </div>
      </x-form>
    </div>
  </div>
</x-layout>