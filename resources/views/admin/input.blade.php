<x-layout>
  <div class="">
    <x-form method="POST" action="{{ route('admin.input') }}" enctype="multipart/form-data">
      <x-input name="nama" label="" type="text" placeholder="Nama"/>
        <x-select 
        name="ayam" 
        label=""
        :options="[
            'hidup' => 'Ayam Hidup', 
            'potong' => 'Ayam Potong', 
        ]"
        selected="{{ old('ayam', $ayam->jenis ?? '') }}"
        placeholder="Pilih jenis ayam"
    />
      <x-select 
        name="satuan" 
        label=""
        :options="[
            'kg' => 'Kilogram', 
            'ekor' => 'Ekor', 
        ]"
        selected="{{ old('satuan', $satuan->jenis ?? '') }}"
        placeholder="Pilih kategori Satuan"
    />
      <x-input name="harga" label="" type="text" placeholder="Harga"/>
      <x-input name="stok" label="" type="number" placeholder="Stok"/>
      <x-input name="deskripsi" label="" type="textarea" placeholder="Deskripsi"/>
      <x-input name="gambar" label="" type="file" placeholder="Foto Produk"/>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </x-form>  
  </div>
</x-layout>