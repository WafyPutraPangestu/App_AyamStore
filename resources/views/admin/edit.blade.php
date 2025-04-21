<x-layout>
  <div class="">
   
    <x-form method="POST" action="/admin/update/{{$produk->id}}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <x-input name="nama" label="" type="text" value="{{ $produk->nama }}" placeholder="Nama"/>
        <x-select 
        name="ayam" 
        label=""
        :options="[
            'hidup' => 'Ayam Hidup', 
            'potong' => 'Ayam Potong', 
        ]"
        selected="{{ old('ayam', $produk->ayam  ?? '') }}"
        placeholder="Pilih jenis ayam"
    />
      <x-select 
        name="satuan" 
        label=""
        :options="[
            'kg' => 'Kilogram', 
            'ekor' => 'Ekor', 
        ]"
        selected="{{ old('satuan', $produk->satuan ?? '') }}"
        placeholder="Pilih kategori Satuan"
    />
      <x-input name="harga" label="" type="text" value="{{ $produk->harga }}" placeholder="Harga"/>
      <x-input name="stok" label="" type="number" value="{{ $produk->stok }}" placeholder="Stok"/>
      <x-input name="deskripsi" label="" type="textarea" value="{{ $produk->deskripsi }}" placeholder="Deskripsi"/>
      <x-input name="gambar" label="" type="file" value="{{ $produk->gambar }}" placeholder="Foto Produk"/>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </x-form>  
  </div>
</x-layout>