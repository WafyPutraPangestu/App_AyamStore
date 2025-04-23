<x-layout>
  <div class="">
    <x-form method="POST" action="{{ route('admin.input') }}" enctype="multipart/form-data">
      <x-input name="nama_produk" label="" type="text" placeholder="Nama"/>
      <x-input name="harga" label="" type="text" placeholder="Harga"/>
      <x-input name="stok" label="" type="number" placeholder="Stok"/>
      <x-input name="deskripsi" label="" type="textarea" placeholder="Deskripsi"/>
      <x-input name="gambar" label="" type="file" placeholder="Foto Produk"/>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </x-form>  
  </div>
</x-layout>