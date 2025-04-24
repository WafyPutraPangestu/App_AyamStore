<x-layout>
  <div class="">
    <x-form method="POST" action="{{ route('admin.update', $produk->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <x-input 
        name="nama_produk" 
        label="" 
        type="text" 
        placeholder="Nama" 
        :value="$produk->nama_produk" 
      />

      <x-input 
        name="harga" 
        label="" 
        type="text" 
        placeholder="Harga" 
        :value="$produk->harga" 
      />

      <x-input 
        name="stok" 
        label="" 
        type="number" 
        placeholder="Stok" 
        :value="$produk->stok" 
      />

      <x-input 
        name="deskripsi" 
        label="" 
        type="textarea" 
        placeholder="Deskripsi" 
        :value="$produk->deskripsi" 
      />

      <div class="mb-4">
        <p class="text-sm text-gray-500 mb-1">Foto saat ini:</p>
        @if ($produk->gambar)
          <img src="{{ asset('storage/images/' . $produk->gambar) }}" class="h-24 rounded" alt="Gambar produk lama">
        @else
          <p class="text-gray-400">Belum ada gambar</p>
        @endif
      </div>

      <x-input 
        name="gambar" 
        label="" 
        type="file" 
        placeholder="Ganti Foto Produk (Opsional)"
      />

      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </x-form>  
  </div>
</x-layout>
