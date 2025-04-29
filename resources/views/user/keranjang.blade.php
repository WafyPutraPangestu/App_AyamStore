<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Keranjang Belanja Anda</h1>

        @if($keranjangs == null || $keranjangs->items->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-10 text-center border border-gray-200">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="mt-5 text-xl font-medium text-gray-600">Keranjang Anda masih kosong.</p>
                <p class="mt-2 text-gray-500">Sepertinya Anda belum menambahkan produk apapun.</p>
                <a href="{{ route('user.katalog') }}" class="mt-8 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 lg:items-start">
                <div class="lg:col-span-8">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative mb-5 shadow-sm" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative mb-5 shadow-sm" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="bg-blue-50 border border-blue-300 text-blue-800 px-4 py-3 rounded-lg relative mb-5 shadow-sm" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex items-center justify-between p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="select-all" class="text-gray-700 font-medium select-none cursor-pointer">Pilih Semua</label>
                        </div>

                        <form id="bulk-delete-form" action="{{ route('keranjang.bulkDestroy') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="selected_ids" id="selected-ids">
                            <button type="submit" class="inline-flex items-center gap-1.5 text-sm bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out" onclick="return confirm('Yakin ingin menghapus item yang dipilih?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus (<span id="bulk-delete-count">0</span>)
                            </button>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @php $totalKeseluruhan = 0; @endphp
                        @foreach ($keranjangs->items as $item)
                            @php
                                $isValidProduct = !is_null($item->produk);
                                $harga = $isValidProduct ? (int) preg_replace('/[^\d]/', '', $item->produk->harga) : 0;
                                $qty = (int) $item->quantity;
                                $subtotal = $harga * $qty;
                                if ($isValidProduct) { $totalKeseluruhan += $subtotal; }
                                $gambarUrl = $isValidProduct && $item->produk->gambar ? asset('storage/images/'. $item->produk->gambar) : asset('images/placeholder-ayam.png');
                                $namaProduk = $isValidProduct ? $item->produk->nama_produk : 'Produk Tidak Ditemukan';
                                $deskripsiProduk = $isValidProduct ? Str::limit($item->produk->deskripsi, 50) : 'Deskripsi Tidak Tersedia';
                            @endphp

                            <div class="item-card bg-white shadow-sm rounded-lg p-4 border border-gray-200 flex flex-col sm:flex-row items-center gap-4 transition duration-300 ease-in-out hover:shadow-md relative {{ !$isValidProduct ? 'opacity-70 bg-gray-50 border-red-200' : '' }}">
                                <div class="flex-shrink-0 self-start sm:self-center">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->produk_id }}" class="item-checkbox form-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" data-item-id="{{ $item->id }}" {{ !$isValidProduct ? 'disabled' : '' }}>
                                </div>

                                <div class="flex-shrink-0">
                                    <img src="{{ $gambarUrl }}" alt="{{ $namaProduk }}" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-md border border-gray-100">
                                </div>

                                <div class="flex-grow text-center sm:text-left w-full sm:w-auto">
                                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 hover:text-indigo-600 transition duration-200">{{ $namaProduk }}</h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $deskripsiProduk }}</p>
                                    <p class="text-sm sm:text-md font-medium text-indigo-600 mt-2">Harga: Rp {{ number_format($harga, 0, ',', '.') }}</p>

                                    @if ($isValidProduct)
                                    <div class="mt-3 flex items-center justify-center sm:justify-start space-x-1">
                                        <button type="button"
                                                class="decrement-quantity p-1.5 border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-1 focus:ring-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                                data-id="{{ $item->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                        </button>

                                        <span class="item-quantity-display inline-block w-10 text-center text-sm font-medium border-y border-gray-300 data-stok py-1.5"
                                              data-id="{{ $item->id }}"
                                              data-harga="{{ $harga }}"
                                              data-stok="{{ $item->produk->stok ?? 0 }}">
                                            {{ $item->quantity }}
                                        </span>

                                        <button type="button"
                                                class="increment-quantity p-1.5 border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-1 focus:ring-indigo-300"
                                                data-id="{{ $item->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                    @else
                                        <p class="mt-3 text-sm text-red-600 font-medium">Produk tidak tersedia</p>
                                    @endif
                                </div>

                                <div class="flex-shrink-0 text-center sm:text-right mt-3 sm:mt-0 ml-auto">
                                    @if ($isValidProduct)
                                        <p class="text-base sm:text-lg font-semibold text-gray-900 subtotal mb-3" id="subtotal-{{ $item->id }}">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    @endif
                                    <form action="{{ route('keranjang.destroy', ['product_id' => $item->produk_id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-1 focus:ring-red-300" title="Hapus item" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                @if (!$isValidProduct)
                                    <div class="absolute inset-0 bg-gray-100 bg-opacity-50 flex items-center justify-center text-red-700 font-medium p-4 rounded-lg text-center">
                                        Produk ini mungkin telah dihapus. <br> Harap hapus dari keranjang.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 lg:sticky lg:top-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b pb-3">Ringkasan Belanja</h2>

                        <div class="flex justify-between items-center border-t border-gray-200 pt-4 mb-6">
                            <span class="text-lg font-medium text-gray-900">Total</span>
                            <span id="total-keseluruhan" class="text-2xl font-bold text-indigo-700">
                                Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                            </span>
                        </div>

                        <form method="POST" id="checkout-form">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-70 disabled:cursor-not-allowed"
                                    id="checkout-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Lanjut ke Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    
    <script>
     document.addEventListener("DOMContentLoaded", function () {
    const selectAllCheckbox = document.getElementById("select-all");
    const itemCheckboxes = document.querySelectorAll(".item-checkbox");
    const incrementButtons = document.querySelectorAll(".increment-quantity");
    const decrementButtons = document.querySelectorAll(".decrement-quantity");
    const quantityDisplays = document.querySelectorAll(".item-quantity-display");
    const bulkDeleteForm = document.getElementById("bulk-delete-form");
    const selectedIdsInputBulkDelete = document.getElementById("selected-ids");
    const bulkDeleteCountSpan = document.getElementById("bulk-delete-count");
    const checkoutForm = document.getElementById("checkout-form");
    const checkoutButton = document.getElementById("checkout-button");
    const totalKeseluruhanEl = document.getElementById("total-keseluruhan");

    const debounceTimeouts = {};

    function getQuantityInfo(itemId) {
        const displaySpan = document.querySelector(`.item-quantity-display[data-id="${itemId}"]`);
        if (!displaySpan) return { quantity: 0, maxStock: 0, harga: 0, displaySpan: null };

        const quantity = parseInt(displaySpan.textContent) || 0;
        const maxStock = parseInt(displaySpan.dataset.stok) || 0; // Ambil data stok
        const harga = parseInt(displaySpan.dataset.harga) || 0;
        return { quantity, maxStock, harga, displaySpan };
    }

    function setDisplayedQuantity(itemId, newQuantity) {
        const { maxStock, displaySpan } = getQuantityInfo(itemId);
        if (!displaySpan) return;

        displaySpan.textContent = newQuantity;

        const itemContainer = displaySpan.closest('.item-card');
        if (!itemContainer) return;

        const decrementButton = itemContainer.querySelector(`.decrement-quantity[data-id="${itemId}"]`);
        const incrementButton = itemContainer.querySelector(`.increment-quantity[data-id="${itemId}"]`);

        if (decrementButton) {
            decrementButton.disabled = (newQuantity <= 1);
        }
        if (incrementButton) {
            incrementButton.disabled = (newQuantity >= maxStock);
        }
    }

    function updateSubtotal(itemId) {
        const { quantity, harga, displaySpan } = getQuantityInfo(itemId);
        if (!displaySpan) return;

        const subtotalEl = document.getElementById(`subtotal-${itemId}`);
        if (subtotalEl) {
            const subtotal = harga * quantity;
            subtotalEl.textContent = `Rp ${subtotal.toLocaleString("id-ID")}`;
        }
    }

    function updateTotal() {
        let total = 0;
        itemCheckboxes.forEach((checkbox) => {
            if (checkbox.checked && !checkbox.disabled) {
                const itemId = checkbox.dataset.itemId;
                const { quantity, harga } = getQuantityInfo(itemId);
                total += harga * quantity;
            }
        });

        if (totalKeseluruhanEl) {
            totalKeseluruhanEl.textContent = `Rp ${total.toLocaleString("id-ID")}`;
        }
        if (checkoutButton) {
            checkoutButton.disabled = total === 0;
        }
    }

    function sendUpdateQuantityRequest(itemId, newQuantity) {
        if (debounceTimeouts[itemId]) clearTimeout(debounceTimeouts[itemId]);

        debounceTimeouts[itemId] = setTimeout(() => {
            console.log(`Sending update quantity request for item ${itemId} to ${newQuantity}`);
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error("CSRF token not found!");
                alert("Terjadi kesalahan: Token keamanan tidak ditemukan.");
                return;
            }

            fetch(`/keranjang/update-quantity/${itemId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
                },
                body: JSON.stringify({ quantity: newQuantity }),
            })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error("Backend error data:", err);
                        // KEMBALIIN QUANTITY KALO ERORR
                        const { quantity: originalQuantity } = getQuantityInfo(itemId); // Get current (failed) qty
                        setDisplayedQuantity(itemId, originalQuantity - (newQuantity > originalQuantity ? 1 : -1) ); // Revert
                        updateSubtotal(itemId);
                        updateTotal();
                        throw new Error(err.message || `Gagal memperbarui kuantitas (Server Error). Status: ${response.status}`);
                    }).catch(() => {
                        throw new Error(`Gagal memperbarui kuantitas (Server Error). Status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    console.log(`Quantity item ${itemId} berhasil diupdate di backend.`);
                     if (typeof data.new_stock !== 'undefined') {
                        const { displaySpan } = getQuantityInfo(itemId);
                        if(displaySpan) {
                            displaySpan.dataset.stok = data.new_stock;
                            setDisplayedQuantity(itemId, newQuantity);
                        }
                    }
                } else {
                    console.error(`Gagal update quantity di backend untuk item ${itemId}:`, data.error);
                    alert(data.error || 'Gagal memperbarui kuantitas item.');
                    const { quantity: currentDisplayQty } = getQuantityInfo(itemId);
                    if (currentDisplayQty !== data.corrected_quantity) { 
                        setDisplayedQuantity(itemId, data.corrected_quantity || currentDisplayQty - (newQuantity > currentDisplayQty ? 1 : -1));
                        updateSubtotal(itemId);
                        updateTotal();
                    }
                }
            })
            .catch((error) => {
                console.error(`Gagal update quantity (fetch error) untuk item ${itemId}:`, error);
                alert(error.message || 'Terjadi kesalahan jaringan saat memperbarui kuantitas item.');
            });
        }, 500); 
    }

    function updateBulkDeleteVisibility() {
        const selectedItemIds = [...itemCheckboxes]
            .filter(checkbox => checkbox.checked && !checkbox.disabled)
            .map(checkbox => checkbox.dataset.itemId);

        const count = selectedItemIds.length;

        if (bulkDeleteForm && selectedIdsInputBulkDelete) {
            if (count > 0) {
                bulkDeleteForm.classList.remove("hidden");
                selectedIdsInputBulkDelete.value = selectedItemIds.join(",");
                if (bulkDeleteCountSpan) bulkDeleteCountSpan.textContent = count;
            } else {
                bulkDeleteForm.classList.add("hidden");
                selectedIdsInputBulkDelete.value = "";
                if (bulkDeleteCountSpan) bulkDeleteCountSpan.textContent = 0;
            }
        }
        if (selectAllCheckbox) {
             const totalCheckboxes = [...itemCheckboxes].filter(cb => !cb.disabled).length;
             selectAllCheckbox.checked = (totalCheckboxes > 0 && count === totalCheckboxes); // Hanya checked jika semua yg enable terpilih
             selectAllCheckbox.indeterminate = (count > 0 && count < totalCheckboxes);
        }
    }

    // --- EVENT LISTENERS ---

    incrementButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const { quantity: currentQuantity, maxStock } = getQuantityInfo(itemId);

            // --- MULAI DEBUGGING ---
            console.log(`Tombol + diklik untuk Item ID: ${itemId}`);
            console.log(`Kuantitas Saat Ini: ${currentQuantity}`);
            const displaySpan = document.querySelector(`.item-quantity-display[data-id="${itemId}"]`);
            console.log(`Nilai data-stok mentah: ${displaySpan ? displaySpan.dataset.stok : 'SPAN TIDAK DITEMUKAN'}`);
            console.log(`Max Stock (setelah parseInt): ${maxStock}`);
            // --- AKHIR DEBUGGING ---

            // Cek Stok sebelum menambah
            if (maxStock <= 0) {
                 console.log(`Peringatan: Max Stock terdeteksi 0 atau negatif (${maxStock}). Anggap stok habis.`);
                 // Jika maxStock 0, mungkin memang habis atau data tidak valid.
                 alert(`Stok untuk item ini habis atau tidak tersedia.`);
                 return; // Hentikan proses
            }

            if (currentQuantity >= maxStock) {
                console.log(`Pengecekan Stok Gagal: ${currentQuantity} >= ${maxStock}`); // Tambahkan log ini
                alert(`Stok untuk item ini hanya ${maxStock}. Anda tidak bisa menambahkan lagi.`);
                return; // Hentikan proses jika stok tidak cukup
            }

            console.log(`Pengecekan Stok Lolos: ${currentQuantity} < ${maxStock}. Menambah kuantitas...`); // Tambahkan log ini

            const newQuantity = currentQuantity + 1;
            setDisplayedQuantity(itemId, newQuantity); // Update tampilan & disable state
            updateSubtotal(itemId);
            updateTotal();
            sendUpdateQuantityRequest(itemId, newQuantity);
        });
    });

    decrementButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const { quantity: currentQuantity } = getQuantityInfo(itemId);

            if (currentQuantity <= 1) return; // Double check

            const newQuantity = currentQuantity - 1;
            setDisplayedQuantity(itemId, newQuantity); // Update tampilan & disable state (termasuk re-enable increment)
            updateSubtotal(itemId);
            updateTotal();
            sendUpdateQuantityRequest(itemId, newQuantity);
        });
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function () {
            itemCheckboxes.forEach((checkbox) => {
                if (!checkbox.disabled) {
                    checkbox.checked = selectAllCheckbox.checked;
                }
            });
            updateTotal();
            updateBulkDeleteVisibility();
        });
    }

    itemCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            updateTotal();
            updateBulkDeleteVisibility();
        });
    });

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const checked = document.querySelectorAll(".item-checkbox:checked:not(:disabled)");

            if (checked.length === 0) {
                alert("Pilih minimal satu item yang valid untuk checkout.");
                return;
            }

            checkoutButton.disabled = true;
            checkoutButton.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

            const selectedItemsData = [...checked].map((checkbox) => {
                 const itemContainer = checkbox.closest('.item-card');
                 const quantitySpan = itemContainer.querySelector('.item-quantity-display');
                 const quantity = quantitySpan ? (parseInt(quantitySpan.textContent) || 1) : 1;
                 return {
                     produk_id: checkbox.value,
                     quantity: quantity,
                 };
             });

            let totalForCheckout = 0;
             selectedItemsData.forEach(item => {
                 const checkbox = document.querySelector(`.item-checkbox[value="${item.produk_id}"]`);
                 if (checkbox) {
                     const itemContainer = checkbox.closest('.item-card');
                     const quantitySpan = itemContainer.querySelector('.item-quantity-display');
                     if (quantitySpan) {
                         const harga = parseInt(quantitySpan.dataset.harga) || 0;
                         totalForCheckout += harga * item.quantity;
                     }
                 }
             });

            console.log("Selected items for checkout:", selectedItemsData);
            console.log("Calculated total for checkout:", totalForCheckout);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error("CSRF token not found for checkout!");
                alert("Terjadi kesalahan: Token keamanan tidak ditemukan.");
                 checkoutButton.disabled = false;
                 checkoutButton.innerHTML = 'Lanjut ke Checkout'; // Reset button text without icon
                 return;
            }

            fetch("{{ route('checkout.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
                },
                body: JSON.stringify({
                    selected_items: selectedItemsData,
                }),
            })
            .then(response => {
                 if (response.status === 409) {
                     return response.json().then(data => {
                         alert(data.message);
                         if (data.order_id) {
                             window.location.href = "{{ route('user.order-form', ':order_id') }}".replace(':order_id', data.order_id);
                         } else {
                             console.error("409 response did not contain order_id.");
                             window.location.href = "{{ route('user.riwayat') }}";
                         }
                         return Promise.reject('Pending order exists, redirecting.');
                     });
                 }
                 if (!response.ok) {
                      // Coba parse error dari backend
                     return response.json().then(err => {
                        throw new Error(err.error || `Terjadi kesalahan server saat checkout. Status: ${response.status}`);
                     }).catch(() => {
                         // Jika backend tidak kirim JSON error
                         throw new Error(`Terjadi kesalahan server saat checkout. Status: ${response.status}`);
                     });
                 }
                 return response.json();
            })
            .then((data) => {
                 if (data.success) {
                     console.log("Checkout berhasil diproses:", data);
                     if (data.redirect_url) {
                         window.location.href = data.redirect_url;
                     } else if (data.order_id) {
                          window.location.href = "{{ route('user.order-form', ':order_id') }}".replace(':order_id', data.order_id);
                     } else {
                         console.error("Backend success response missing redirect_url or order_id.");
                         alert("Checkout berhasil, tetapi gagal mengarahkan.");
                         checkoutButton.disabled = false;
                         checkoutButton.innerHTML = 'Lanjut ke Checkout';
                     }
                 } else {
                     // Ini seharusnya ditangkap oleh !response.ok, tapi sebagai fallback
                     console.error("Gagal checkout (data.success false):", data.error);
                     alert(data.error || 'Gagal memproses checkout.');
                     checkoutButton.disabled = false;
                     checkoutButton.innerHTML = 'Lanjut ke Checkout';
                 }
            })
            .catch((error) => {
                console.error("Error during checkout fetch:", error);
                 if (error !== 'Pending order exists, redirecting.') { // Jangan tampilkan alert jika redirect
                    alert(error.message || 'Terjadi kesalahan jaringan atau server saat checkout.');
                 }
                 checkoutButton.disabled = false;
                 checkoutButton.innerHTML = 'Lanjut ke Checkout';
            });
        });
    }

    // --- INITIALIZATION ---
    function initializeButtonStates() {
        quantityDisplays.forEach(span => {
            const itemId = span.dataset.id;
            const { quantity, maxStock } = getQuantityInfo(itemId);
            setDisplayedQuantity(itemId, quantity); // This also sets initial button disabled state
        });
    }

    initializeButtonStates(); // Set initial button disable states
    updateTotal();
    updateBulkDeleteVisibility();
});
    </script>
</x-layout>