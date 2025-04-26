<x-layout>
  {{-- Pastikan notifikasi sukses/error modern Anda juga terintegrasi,
       misalnya di dalam x-layout atau di bagian atas file ini seperti sebelumnya --}}

  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center"> {{-- Ubah gradient ending, tambahkan flexbox untuk centering vertikal --}}
    <div class="max-w-3xl w-full mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden"> {{-- Lebar maks diperluas sedikit, bayangan lebih kuat --}}
      <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white"> {{-- Header dengan gradient --}}
        <h1 class="text-3xl md:text-4xl font-bold text-center tracking-tight">Input Produk Baru</h1> {{-- Ukuran teks responsif, tracking tighter --}}
      </div>

      <div class="p-8 md:p-10"> {{-- Padding responsif --}}
        <x-form method="POST" action="{{ route('admin.input') }}" enctype="multipart/form-data" class="space-y-6">
          @csrf

          <div class="space-y-6"> {{-- Konsistenkan spasi vertikal form --}}
            <div class="group">
              {{-- Komponen Input: Sesuaikan styling di dalam komponennya jika perlu --}}
              {{-- Rekomendasi: Label di atas input, border halus saat normal, border/ring berwarna saat focus --}}
              <x-input
                name="nama_produk"
                label="Nama Produk" {{-- Tambahkan label untuk aksesibilitas dan kejelasan --}}
                type="text"
                placeholder="Masukkan nama produk..." {{-- Placeholder lebih deskriptif --}}
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-gray-800 placeholder-gray-400" {{-- Styling input field --}}
              />
            </div>

            <div class="group">
              <x-input
                name="harga"
                label="Harga Produk" {{-- Tambahkan label --}}
                type="text" {{-- Atau type="number" jika validasi di handle --}}
                placeholder="Masukkan harga produk..." {{-- Placeholder lebih deskriptif --}}
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-gray-800 placeholder-gray-400"
              />
            </div>

            <div class="group">
              <x-input
                name="stok"
                label="Stok Produk" {{-- Tambahkan label --}}
                type="number"
                placeholder="Jumlah stok..." {{-- Placeholder lebih deskriptif --}}
                min="0" {{-- Tambahkan validasi min --}}
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-gray-800 placeholder-gray-400"
              />
            </div>

            <div class="group">
              <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label> {{-- Label terpisah untuk textarea --}}
              <textarea
                name="deskripsi"
                id="deskripsi" {{-- Tambahkan id --}}
                placeholder="Tulis deskripsi lengkap produk..." {{-- Placeholder lebih deskriptif --}}
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 min-h-[120px] text-gray-800 placeholder-gray-400 resize-y" {{-- Styling textarea, min-height sedikit disesuaikan, allow vertical resize --}}
              ></textarea>
            </div>

            <div class="group">
              <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label> {{-- Label terpisah --}}
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-all duration-200 cursor-pointer relative"> {{-- Styling drag-drop area, tambahkan relative --}}
                <input
                  type="file"
                  name="gambar"
                  id="gambar"
                  class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" {{-- Input file full cover area --}}
                  accept="image/*"
                />
                <div class="pointer-events-none"> {{-- Div ini tidak akan menangkap klik, sehingga klik diteruskan ke input file di bawahnya --}}
                  <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p class="mt-2 text-sm text-gray-600">Klik atau seret gambar ke sini</p> {{-- Teks lebih jelas --}}
                  <p class="text-xs text-gray-500 mt-1">PNG, JPG atau WEBP (Max. 2MB)</p>
                </div>
                <p id="file-name" class="mt-3 text-sm text-blue-600 hidden font-medium"></p> {{-- Jarak dan font medium --}}
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4 mt-8"> {{-- Layout tombol responsif, spasi antar tombol --}}
            {{-- Tombol Batal --}}
            {{-- Gunakan tag <a> jika hanya navigasi, atau <button type="button"> jika ada aksi JS --}}
            <a
              href="{{ url()->previous() }}" {{-- Contoh kembali ke halaman sebelumnya --}}
              class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 ease-in-out text-center" {{-- Styling tombol sekunder --}}
            >
              Batal
            </a>
            {{-- Tombol Simpan --}}
            <button
              type="submit"
              class="inline-flex justify-center items-center px-6 py-3 border border-transparent bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-0.5" {{-- Tambahkan border transparent, focus ring --}}
            >
              Simpan Produk
            </button>
          </div>
        </x-form>
      </div>
    </div>
  </div>

  <script>
    // Script untuk menampilkan nama file yang dipilih
    document.getElementById('gambar').addEventListener('change', function() {
      const fileInput = this;
      const fileNameElement = document.getElementById('file-name');

      if (fileInput.files && fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        fileNameElement.textContent = 'File terpilih: ' + fileName; // Teks lebih jelas
        fileNameElement.classList.remove('hidden');
      } else {
        fileNameElement.textContent = '';
        fileNameElement.classList.add('hidden');
      }
    });
  </script>
</x-layout>