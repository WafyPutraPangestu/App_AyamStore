{{-- Starting with the user's provided code (first block in previous message) --}}

<x-layout>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja Anda</h1>

      @if($keranjangs == null || $keranjangs->items->isEmpty())
          <div class="bg-white shadow-lg rounded-lg p-8 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <p class="mt-4 text-lg font-medium text-gray-600">Keranjang Anda masih kosong.</p>
              <a href="{{ route('user.katalog') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">Mulai Belanja</a>
          </div>
      @else
           {{-- Added session messages based on previous versions --}}
           @if (session('success'))
               <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                   <span class="block sm:inline">{{ session('success') }}</span>
               </div>
           @endif
           @if (session('error'))
               <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                   <span class="block sm:inline">{{ session('error') }}</span>
               </div>
           @endif
            @if (session('info'))
               <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                   <span class="block sm:inline">{{ session('info') }}</span>
               </div>
           @endif

          <div class="mb-4 flex items-center gap-2 justify-between">
              <div class="flex items-center gap-2">
                  <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600 rounded"> {{-- Added rounded class back --}}
                  <label for="select-all" class="text-gray-700 font-medium select-none">Pilih Semua</label> {{-- Added select-none --}}

              </div>
               {{-- Bulk delete form - keep it, will fix JS --}}
               {{-- Removed redundant @if(!$keranjangs->items->isEmpty()) check here, already done above --}}
              <form id="bulk-delete-form" action="{{ route('keranjang.bulkDestroy') }}" method="POST" class="mt-6 text-right hidden">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="selected_ids" id="selected-ids">
                  <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">
                      Hapus Yang Dipilih
                  </button>
              </form>


          </div>

          <div class="space-y-4">
              @php $totalKeseluruhan = 0; @endphp
              @foreach ($keranjangs->items as $item)
                  @php
                      // Ensure product exists before accessing properties
                      $harga = 0;
                      if ($item->produk) {
                          $harga = (int) preg_replace('/[^\d]/', '', $item->produk->harga);
                      }
                      $qty = (int) $item->quantity;
                      $subtotal = $harga * $qty;
                      $totalKeseluruhan += $subtotal;
                  @endphp

                   {{-- Main item container --}}
                   <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 transition duration-300 ease-in-out hover:shadow-xl">
                      <div class="flex-shrink-0">
                           {{-- Add data-item-id="{{ $item->id }}" here for bulk delete JS --}}
                          <input type="checkbox" name="selected_items[]" value="{{ $item->produk_id }}" class="item-checkbox form-checkbox h-5 w-5 text-blue-600 rounded" data-item-id="{{ $item->id }}"> {{-- Added rounded class --}}
                      </div>

                      <div class="flex-shrink-0">
                           {{-- Keep original image tag --}}
                          <img src="{{ asset('storage/images/'. $item->produk->gambar) }}" alt="{{ $item->produk ? $item->produk->nama_produk : 'Produk Tidak Ditemukan' }}" class="w-24 h-24 object-cover rounded-md border border-gray-200">
                      </div>

                      <div class="flex-grow text-center md:text-left">
                           {{-- Ensure product data exists --}}
                          <h2 class="text-lg font-semibold text-gray-800">{{ $item->produk ? $item->produk->nama_produk : 'Produk Tidak Ditemukan' }}</h2>
                          <p class="text-sm text-gray-500 mt-1">{{ $item->produk ? $item->produk->deskripsi : 'Deskripsi Tidak Tersedia' }}</p>
                          <p class="text-md font-medium text-blue-600 mt-2">Harga: Rp {{ $item->produk ? $item->produk->harga : '0' }}</p>

                          {{-- REPLACE the content of this form with +/- buttons and span --}}
                          <form action="#" method="POST" class="mt-3 flex items-center justify-center md:justify-start space-x-2">
                              @csrf
                              @method('PUT') {{-- Keep method PUT for fetch --}}
                              {{-- Removed original label, input type="number", and update button --}}

                              {{-- NEW HTML for +/- Buttons and Span --}}
                               <label class="text-sm font-medium text-gray-700 select-none">Jumlah:</label>
                               <button type="button"
                                       class="decrement-quantity p-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md shadow-sm transition duration-150 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                       data-id="{{ $item->id }}"> {{-- Item ID --}}
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                               </button>
                               <span class="item-quantity-display w-8 text-center border border-gray-300 rounded-md py-1"
                                     data-id="{{ $item->id }}" {{-- Item ID --}}
                                     data-harga="{{ $harga }}"> {{-- Numeric price --}}
                                     {{ $item->quantity }}
                               </span>
                                <button type="button"
                                       class="increment-quantity p-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md shadow-sm transition duration-150 ease-in-out transform hover:scale-105"
                                       data-id="{{ $item->id }}"> {{-- Item ID --}}
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                               </button>
                          </form>

                      </div>

                      <div class="text-center md:text-right space-y-2 md:space-y-3">
                           {{-- Ensure product data exists --}}
                          @if ($item->produk)
                              <p class="text-lg font-semibold text-gray-900 subtotal" id="subtotal-{{ $item->id }}">
                                  Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}
                              </p>

                               {{-- Single delete form --}}
                               <form action="{{ route('keranjang.destroy', ['product_id' => $item->produk->id]) }}" method="POST" class="inline-block">
                                   @csrf
                                   @method('DELETE')
                                   <button type="submit" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                       </svg>
                                   </button>
                               </form>
                          @else
                               {{-- Display alert and only delete button if product is missing --}}
                              <p class="text-red-500 text-sm">Produk tidak ditemukan.</p>
                               <form action="{{ route('keranjang.destroy', ['product_id' => $item->produk_id]) }}" method="POST" class="inline-block">
                                   @csrf
                                   @method('DELETE')
                                   <button type="submit" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                       </svg>
                                   </button>
                               </form>
                          @endif
                      </div>
                  </div>
                   {{-- The product not found alert was moved out of the main item div --}}
                   @if (!$item->produk)
                       <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mt-2" role="alert">
                           <span class="block sm:inline">Produk untuk item ini tidak ditemukan. Mungkin sudah dihapus. Mohon hapus item ini dari keranjang.</span>
                       </div>
                   @endif
              @endforeach
          </div>

          {{-- Ringkasan Belanja dan Checkout - Keep this HTML structure --}}
          <div class="mt-8 bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row justify-between items-center">
              <h2 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">Ringkasan Belanja</h2>
              <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-8">
                  <div class="flex justify-between items-center border-t md:border-none border-gray-200 pt-4 md:pt-0 w-full md:w-auto">
                      <span class="text-lg font-medium text-gray-700">Total Keseluruhan:</span>
                      <span id="total-keseluruhan" class="text-2xl font-bold text-blue-700 ml-2">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                  </div>
                  {{-- Checkout Form - Keep this HTML structure --}}
                  <form method="POST" id="checkout-form">
                       @csrf {{-- Add CSRF token --}}
                       {{-- selected_ids input is not used by the JS checkout logic here --}}
                       {{-- <input type="hidden" name="selected_ids" id="checkout-selected-ids"> --}}
                      <button type="submit" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl w-full md:w-auto text-center">
                          Lanjut ke Checkout
                      </button>
                  </form>
              </div>
          </div>
      @endif
  </div>

  <script>
      document.addEventListener("DOMContentLoaded", function () {
          // --- SELECTORS ---
          const selectAllCheckbox = document.getElementById("select-all");
          const itemCheckboxes = document.querySelectorAll(".item-checkbox"); // Checkbox produk (value=produk_id, data-item-id=item_id)
          // Select tombol +/- dan span display quantity
          const incrementButtons = document.querySelectorAll(".increment-quantity"); // Select buttons with this class
          const decrementButtons = document.querySelectorAll(".decrement-quantity"); // Select buttons with this class
          const quantityDisplays = document.querySelectorAll(".item-quantity-display"); // Select spans with this class

          // Remove the old quantityInputs selector
          // const quantityInputs = document.querySelectorAll(".quantity-input"); // REMOVE THIS LINE

          const bulkDeleteForm = document.getElementById("bulk-delete-form");
          const selectedIdsInputBulkDelete = document.getElementById("selected-ids");

          // Keep checkout form selector
          const checkoutForm = document.getElementById("checkout-form");

          const debounceTimeouts = {};


          // --- HELPER FUNCTIONS ---

          // Function to get quantity from the display span (New logic)
          function getDisplayedQuantity(itemId) {
              const displaySpan = document.querySelector(`.item-quantity-display[data-id="${itemId}"]`);
              return displaySpan ? parseInt(displaySpan.textContent) || 0 : 0;
          }

          // Function to set quantity on the display span (New logic)
          function setDisplayedQuantity(itemId, quantity) {
              const displaySpan = document.querySelector(`.item-quantity-display[data-id="${itemId}"]`);
              if (displaySpan) {
                  displaySpan.textContent = quantity;
                  // Add logic to disable decrement button if quantity is 1
                  const itemContainer = displaySpan.closest('.flex'); // Find the item row
                  if (itemContainer) {
                      const decrementButton = itemContainer.querySelector('.decrement-quantity');
                      if (decrementButton) {
                          decrementButton.disabled = (quantity <= 1);
                      }
                  }
              }
          }


          // Function to update subtotals and total (MODIFIED: reads from display spans)
          function updateSubtotalAndTotal() {
              // Loop through ALL quantity display spans
              quantityDisplays.forEach((span) => { // Loop through quantity display spans
                  const id = span.dataset.id; // This is KeranjangsItem ID
                  // Ambil harga dari data attribute di span (MODIFIED: read from span data-harga)
                  const harga = parseInt(span.dataset.harga) || 0;
                  const quantity = parseInt(span.textContent) || 0; // Read quantity from span text

                  const subtotal = harga * quantity;
                  const subtotalEl = document.getElementById(`subtotal-${id}`);
                   if (subtotalEl) { // Add check just in case product is missing
                      subtotalEl.textContent = `Subtotal: Rp ${subtotal.toLocaleString("id-ID")}`;
                  }
              });

              // Then calculate total based on CHECKED items
              updateTotal();
          }

          // Function to update total (MODIFIED: reads from display spans for checked items)
          function updateTotal() {
              let total = 0;
              // Loop through ALL checkboxes
              itemCheckboxes.forEach((checkbox) => {
                  if (checkbox.checked) {
                      // Find the item container for this checkbox
                      const itemContainer = checkbox.closest('.flex'); // Assuming item row is .flex
                      if (itemContainer) {
                          // Find the quantity display span within this item container
                          const quantitySpan = itemContainer.querySelector('.item-quantity-display'); // !!! SELECT THE SPAN !!!
                          if (quantitySpan) {
                              const harga = parseInt(quantitySpan.dataset.harga) || 0; // Read price from span data
                              const quantity = parseInt(quantitySpan.textContent) || 0; // Read quantity from span text
                              total += harga * quantity;
                          } else {
                               console.error("Quantity span not found for checked item:", checkbox);
                          }
                      } else {
                           console.error("Item container not found for checked checkbox:", checkbox);
                      }
                  }
              });

              const totalEl = document.getElementById("total-keseluruhan");
              if (totalEl) {
                  totalEl.textContent = `Rp ${total.toLocaleString("id-ID")}`;
              }
          }

          // Function to send update quantity request (with debounce) - NEW FUNCTION
          function sendUpdateQuantityRequest(itemId, newQuantity) {
              if (debounceTimeouts[itemId]) clearTimeout(debounceTimeouts[itemId]);

              debounceTimeouts[itemId] = setTimeout(() => {
                  console.log(`Sending update quantity request for item ${itemId} to ${newQuantity}`);
                  // Using fetch to PUT to the correct route with Item ID
                  fetch(`/keranjang/update-quantity/${itemId}`, { // Use Item ID in URL
                      method: "PUT",
                      headers: {
                          "Content-Type": "application/json",
                          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                      },
                      body: JSON.stringify({ quantity: newQuantity }), // Send new quantity
                  })
                  .then((response) => {
                       if (!response.ok) {
                           // Attempt to read error message from backend response
                            return response.json().then(err => { throw new Error(err.message || `Backend update failed for item ${itemId}. Status: ${response.status}`); });
                       }
                       return response.json();
                  })
                  .then((data) => {
                      if (data.success) {
                          console.log(`Quantity item ${itemId} berhasil diupdate di backend.`);
                          // Optional: Sync display again if backend returns final quantity
                          // setDisplayedQuantity(itemId, data.quantity); // e.g. backend returns data.quantity
                          // updateSubtotalAndTotal(); // Update totals again if display was synced
                      } else {
                          console.error(`Gagal update quantity di backend untuk item ${itemId}:`, data.error);
                          // Revert display quantity on frontend if update failed
                          // This is tricky without knowing the *previous* value easily.
                          // A robust solution would involve storing the previous value or refetching the cart item.
                          alert(data.error || 'Gagal memperbarui kuantitas item.'); // Notify user
                           // You might want to revert the displayed quantity here if the backend failed.
                           // Getting the old value reliably without storing it prior to fetch is hard.
                           // For now, we just alert. User may need to refresh if display is wrong.
                      }
                  })
                  .catch((error) => {
                      console.error(`Gagal update quantity (fetch error) untuk item ${itemId}:`, error);
                       alert(error.message || 'Terjadi kesalahan jaringan saat memperbarui kuantitas item.'); // Notify user
                       // Revert quantity display on network error or other fetch issues - same challenge as above.
                  });
              }, 500); // Debounce time
          }


          // --- EVENT LISTENERS ---

          // Add listeners for increment/decrement buttons - NEW LISTENERS
          incrementButtons.forEach(button => {
              button.addEventListener('click', function() {
                  const itemId = this.dataset.id;
                  const currentQuantity = getDisplayedQuantity(itemId);
                  const newQuantity = currentQuantity + 1;

                  setDisplayedQuantity(itemId, newQuantity); // Update display immediately
                  updateSubtotalAndTotal(); // Update totals immediately

                  sendUpdateQuantityRequest(itemId, newQuantity); // Send debounced request
              });
          });

          decrementButtons.forEach(button => {
              button.addEventListener('click', function() {
                  const itemId = this.dataset.id;
                  const currentQuantity = getDisplayedQuantity(itemId);

                  if (currentQuantity <= 1) {
                      console.log("Cannot decrease quantity below 1.");
                      // Disable the decrement button visually if it's already 1 (handled in setDisplayedQuantity)
                      return; // Stop if quantity is 1
                  }

                  const newQuantity = currentQuantity - 1;

                  setDisplayedQuantity(itemId, newQuantity); // Update display immediately
                  updateSubtotalAndTotal(); // Update totals immediately

                  sendUpdateQuantityRequest(itemId, newQuantity); // Send debounced request
              });
          });


          // Select All checkbox listener (Keep this, same logic)
          if (selectAllCheckbox) {
               selectAllCheckbox.addEventListener("change", function () {
                   itemCheckboxes.forEach((checkbox) => {
                       checkbox.checked = selectAllCheckbox.checked;
                   });
                   updateTotal();
                   updateBulkDeleteVisibility();
               });
          }

          // Individual item checkbox listener (Keep this, same logic)
          itemCheckboxes.forEach((checkbox) => {
              checkbox.addEventListener("change", function () {
                  // Update "Select All" status
                  if (!this.checked) {
                      if(selectAllCheckbox) selectAllCheckbox.checked = false;
                  } else if ([...itemCheckboxes].every((cb) => cb.checked)) {
                      if(selectAllCheckbox) selectAllCheckbox.checked = true;
                  }
                  updateTotal(); // Recalculate total based on checked items
                  updateBulkDeleteVisibility(); // Update visibility of bulk delete button
              });
          });

           // Update visibility of bulk delete form (FIXED: collects Item IDs from checkbox data-item-id)
           function updateBulkDeleteVisibility() {
               const selectedItemIds = [...itemCheckboxes] // Loop through ALL checkboxes
                   .filter(checkbox => checkbox.checked) // Filter for checked ones
                   .map(checkbox => checkbox.dataset.itemId); // !!! GET ITEM ID from data attribute !!!

               const bulkDeleteForm = document.getElementById("bulk-delete-form"); // Re-get inside function just in case
               const selectedIdsInputBulkDelete = document.getElementById("selected-ids"); // Re-get

               if (bulkDeleteForm && selectedIdsInputBulkDelete) { // Check if elements exist
                   if (selectedItemIds.length > 0) {
                       bulkDeleteForm.classList.remove("hidden");
                       selectedIdsInputBulkDelete.value = selectedItemIds.join(","); // Set hidden input value
                   } else {
                       bulkDeleteForm.classList.add("hidden");
                       selectedIdsInputBulkDelete.value = "";
                   }
               }
           }

           // Initial update visibility for bulk delete form
           updateBulkDeleteVisibility();


          // Checkout form submit listener (MODIFIED: reads quantity from display spans)
          // KEEPING THE ORIGINAL REDIRECT LOGIC AS REQUESTED
          if (checkoutForm) { // Check if checkout form exists
              checkoutForm.addEventListener("submit", function (e) {
                  e.preventDefault();

                  const checked = document.querySelectorAll(".item-checkbox:checked");

                  if (checked.length === 0) {
                      alert("Pilih minimal satu item untuk checkout.");
                      return;
                  }

                  // Ambil data item yang dipilih (produk_id dan kuantitas)
                  const selectedItems = [...checked].map((checkbox) => {
                      // checkbox.value is produk_id
                      const itemContainer = checkbox.closest('.flex'); // Find item row
                      // Find the quantity display span within this item row
                      const quantitySpan = itemContainer.querySelector('.item-quantity-display'); // !!! SELECT THE SPAN !!!

                      const quantity = parseInt(quantitySpan.textContent) || 1; // !!! READ FROM TEXT CONTENT !!!

                      return {
                          produk_id: checkbox.value, // Produk ID from checkbox value
                          quantity: quantity,        // Quantity from the quantity display span
                      };
                  });

                  // Calculate total for checkout again from selected items data (optional but good practice)
                  let totalForCheckout = 0;
                   selectedItems.forEach(item => {
                        // To get the price, we need to find the corresponding span again.
                        // This approach is a bit redundant but matches structure.
                        const checkbox = document.querySelector(`.item-checkbox[value="${item.produk_id}"]`); // Find checkbox by produk_id
                         if (checkbox) {
                             const itemContainer = checkbox.closest('.flex');
                              if (itemContainer) {
                                  const quantitySpan = itemContainer.querySelector(`.item-quantity-display[data-id="${checkbox.dataset.itemId}"]`); // Find span by item ID

                                  if(quantitySpan) {
                                      const harga = parseInt(quantitySpan.dataset.harga) || 0;
                                      totalForCheckout += harga * item.quantity;
                                  } else {
                                       console.error('Quantity span not found for item in total calculation:', item);
                                  }
                              }
                         } else {
                              console.error('Checkbox not found for item in total calculation:', item);
                         }
                   });


                  console.log("Selected items for checkout:", selectedItems);
                  console.log("Total for checkout:", totalForCheckout);


                  fetch("/checkout", { // Route to OrderController::store
                      method: "POST",
                      headers: {
                          "Content-Type": "application/json",
                          Accept: "application/json",
                          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                      },
                      body: JSON.stringify({
                          selected_items: selectedItems,
                          total: totalForCheckout, // Use the recalculated total
                      }),
                  })
                  .then((response) => {
                       if (!response.ok) {
                           // Attempt to read error message from backend response
                            return response.json().then(err => { throw new Error(err.error || `Terjadi kesalahan server saat membuat order. Status: ${response.status}`); });
                       }
                       return response.json();
                  })
                  .then((data) => {
                      // !!! KEEPING ORIGINAL REDIRECT LOGIC AS REQUESTED !!!
                       if (data.success) {
                           console.log("Order berhasil dibuat. Redirecting to order form...");
                           if (data.order_id) {
                                window.location.href = `/user/order-form/${data.order_id}`; // Redirect to the order form with order ID
                           } else {
                                console.error("Backend did not return order_id for redirect.");
                                alert("Checkout berhasil, tetapi gagal mengarahkan ke halaman selanjutnya.");
                                // Fallback or show a message
                           }
                       } else {
                           console.error("Gagal membuat order:", data.error);
                           alert(data.error || 'Gagal memproses checkout.'); // Tampilkan pesan error dari backend
                       }
                  })
                  .catch((error) => {
                      console.error("Error saat kirim data checkout:", error);
                      alert(error.message || 'Terjadi kesalahan jaringan atau server saat checkout.'); // Tampilkan pesan error
                  });
              });
          } else {
               console.error("Checkout form element not found!");
          }


          // --- INITIAL CALLS ---
          // Disable decrement button if quantity is 1 on load
          quantityDisplays.forEach(span => {
              const currentQuantity = parseInt(span.textContent) || 0;
              const decrementButton = span.closest('.flex').querySelector('.decrement-quantity');
              if (decrementButton) {
                  decrementButton.disabled = (currentQuantity <= 1);
              }
          });

          // Initial update total when page loads (reads from quantity display spans)
          updateSubtotalAndTotal(); // Reads from spans

      });
  </script>
</x-layout>