<x-layout>
    <x-notifications />

    <!-- Toast Notification Component -->
    <div id="toast-notification" class="fixed top-5 right-0 z-50 transform transition-transform duration-500 translate-x-full max-w-xs sm:max-w-sm md:max-w-md">
        <div class="bg-white border-l-4 border-green-500 rounded-lg shadow-xl p-4 flex items-start space-x-3">
            <div class="flex-shrink-0 text-green-500 pt-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-grow">
                <p class="font-medium text-gray-900" id="toast-title"></p>
                <p class="text-sm text-gray-600 mt-1" id="toast-message"></p>
            </div>
            <button onclick="hideToast()" class="ml-auto text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
        <!-- Decorative Elements -->
        <div class="absolute top-10 left-10 w-40 h-40 bg-blue-100 rounded-full opacity-20 blur-xl"></div>
        <div class="absolute bottom-10 right-10 w-56 h-56 bg-purple-100 rounded-full opacity-20 blur-xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Page Title -->
            <h1 class="text-3xl font-bold mb-8 text-center text-gray-800 transform transition-all duration-300 hover:scale-105">Form Pemesanan</h1>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 transform transition-all duration-300 hover:shadow-xl">
                <!-- Order Items Section -->
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Rincian Pesanan</h2>
                <div class="space-y-6 mb-8">
                    @forelse ($order->items as $item)
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 p-4 border border-gray-200 rounded-xl hover:border-indigo-200 transition-all duration-300">
                            <!-- Product Image -->
                            <div class="overflow-hidden relative aspect-square w-28 h-28 bg-gray-100 rounded-lg flex-shrink-0">
                                <img
                                    src="{{ $item->produk->gambar ? asset('storage/images/'. $item->produk->gambar) : asset('images/default-placeholder.jpg') }}"
                                    alt="{{ $item->produk->nama_produk }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                    loading="lazy"
                                >
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow text-center sm:text-left space-y-1">
                                <h3 class="text-lg font-bold text-gray-800 transition-colors duration-300 hover:text-indigo-600">{{ $item->produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }}</p>
                                <p class="text-base font-semibold text-indigo-600">
                                    Harga: Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-600 font-semibold">
                                    Subtotal: Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <!-- Empty Cart State -->
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="mt-3 text-gray-600 text-lg font-medium">Belum ada item dalam pesanan ini.</p>
                            <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-3 mt-6 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors duration-200 transform hover:scale-105 shadow-md">
                                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.426 1.119 1.004zM8.25 10.5a.75.75 0 01.75-.75h3.75a.75.75 0 01.75.75v6.75a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v-6.75zm9 0a.75.75 0 01.75-.75h.008v6.75h-.008a.75.75 0 01-.75-.75v-6.75z" />
                                </svg>
                                Belanja Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Cost Summary Section -->
                <div class="border-t border-gray-200 pt-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Ringkasan Biaya</h2>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <!-- Product Subtotal -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Subtotal Produk</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <!-- Shipping Cost -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Biaya Pengiriman</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <!-- Shipping Notes -->
                        <div class="text-xs text-gray-500 mt-2 mb-2 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            <p>*Ongkir ayam hidup: Rp 20.000/item</p>
                            <p>*Ongkir ayam potong: Rp 10.000/item</p>
                        </div>
                    </div>

                    <!-- Total Payment -->
                    <div class="flex justify-between items-center text-xl font-bold mt-6 pt-4 border-t border-gray-200">
                        <span class="text-gray-800">Total Pembayaran</span>
                        <span class="text-emerald-600">Rp {{ number_format(($order->total_harga + ($order->ongkir ?? 0)), 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Form for Order Processing -->
                <form method="POST" action="{{ route('user.order-store') }}" class="w-full">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="total_harga" value="{{ $order->total_harga }}">

                    <!-- Shipping Address Section -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Alamat Pengiriman</h2>
                        <div class="border border-gray-300 rounded-lg p-4 hover:border-indigo-400 transition-all duration-300 shadow-sm">
                            <label for="alamat_pengiriman" class="sr-only">Alamat Pengiriman Lengkap</label>
                            <textarea
                                name="alamat_pengiriman"
                                id="alamat_pengiriman"
                                class="w-full p-3 border-none outline-none focus:ring-0 resize-none"
                                rows="4"
                                placeholder="Masukkan alamat pengiriman lengkap"
                                required>{{ old('alamat_pengiriman') }}</textarea>
                        </div>
                        @error('alamat_pengiriman')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Submit Button -->
                        <button type="submit" class="w-full flex items-center justify-center bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:bg-emerald-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Bayar Sekarang
                        </button>

                        <!-- Cancel Button -->
                        <button type="button" onclick="confirmCancel()" class="w-full flex items-center justify-center bg-white border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 shadow-sm">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Batalkan Pesanan
                        </button>
                    </div>
                </form>

                <!-- Hidden Cancel Form -->
                <form method="POST" action="{{ route('user.delete', $order) }}" id="cancel-form" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        function confirmCancel() {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                document.getElementById('cancel-form').submit();
            }
        }

        // Fungsi untuk menampilkan notifikasi
        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('toast-notification');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const borderColor = toast.querySelector('.border-l-4'); // Target the element with border-l-4 class
            const iconContainer = toast.querySelector('.flex-shrink-0'); // Target the icon container

            // Set konten toast
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            // Reset existing color classes
            borderColor.className = borderColor.className.replace(/border-\w+-500/g, '');
            iconContainer.className = iconContainer.className.replace(/text-\w+-500/g, '');

            // Set new color based on type
            if (type === 'success') {
                borderColor.classList.add('border-green-500');
                iconContainer.classList.add('text-green-500');
            } else if (type === 'error') {
                borderColor.classList.add('border-red-500');
                iconContainer.classList.add('text-red-500');
            } else if (type === 'warning') {
                borderColor.classList.add('border-yellow-500');
                iconContainer.classList.add('text-yellow-500');
            }

            // Tampilkan toast
            toast.classList.remove('translate-x-full');

            // Sembunyikan toast setelah 5 detik
            setTimeout(() => {
                hideToast();
            }, 5000);
        }

        function hideToast() {
            const toast = document.getElementById('toast-notification');
            toast.classList.add('translate-x-full');
        }

        // Contoh penggunaan notifikasi ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada parameter status di URL
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const message = urlParams.get('message'); // Get message if available

            if (status === 'success') {
                showToast('Berhasil', message || 'Operasi berhasil.', 'success'); // Use message from URL or default
            } else if (status === 'error') {
                showToast('Gagal', message || 'Terjadi kesalahan.', 'error'); // Use message from URL or default
            } else if (status === 'warning') {
                showToast('Peringatan', message || 'Ada peringatan.', 'warning'); // Added warning type
            }

            // Clear URL parameters after showing toast to prevent it from reappearing on refresh
            if(status) {
                history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</x-layout>