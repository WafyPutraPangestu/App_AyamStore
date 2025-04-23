<x-layout>
  <div class="min-h-screen bg-gray-50">
      <!-- Product Grid -->
      <div class="max-w-7xl mx-auto px-4 py-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
              @foreach ($katalog as $item)
                  <div 
                      x-data="{}"
                      @click="$dispatch('open-modal', { product: {{ $item }} })"
                      class="group relative bg-white rounded-2xl shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer"
                  >
                      <div class="overflow-hidden rounded-2xl">
                          <img 
                              src="{{ asset('storage/images/'. $item->gambar) }}" 
                              alt="{{ $item->nama }}" 
                              class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                          >
                      </div>
                      <div class="p-6">
                          <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama }}</h3>
                          <p class="text-lg font-semibold text-indigo-600">
                              Rp {{ number_format($item->harga, 0, ',', '.') }}
                          </p>
                      </div>
                  </div>
              @endforeach
          </div>
      </div>

      <!-- Product Modal -->
      <div 
          x-data="{ showModal: false, selectedProduct: null, quantity: 1 }"
          x-show="showModal"
          @open-modal.window="showModal = true; selectedProduct = $event.detail.product"
          @keydown.escape.window="showModal = false"
          class="fixed inset-0 z-50 overflow-y-auto"
          style="display: none;"
      >
          <!-- Overlay -->
          <div 
              x-show="showModal"
              x-transition:enter="ease-out duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="ease-in duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="fixed inset-0 bg-black/50 backdrop-blur-sm"
              @click="showModal = false"
          ></div>

          <!-- Modal Content -->
          <div 
              x-show="showModal"
              x-transition:enter="ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave="ease-in duration-200"
              x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              class="relative mx-auto my-8 max-w-md w-full"
          >
              <div class="bg-white rounded-3xl shadow-2xl p-6">
                  <!-- Product Info -->
                  <div class="flex gap-4 mb-6">
                      <img 
                          :src="'storage/images/' + selectedProduct?.gambar" 
                          :alt="selectedProduct?.nama"
                          class="w-24 h-24 object-cover rounded-xl"
                      >
                      <div>
                          <h2 class="text-2xl font-bold text-gray-800" x-text="selectedProduct?.nama"></h2>
                          <p class="text-xl font-semibold text-indigo-600">
                              Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedProduct?.harga)"></span>
                          </p>
                      </div>
                  </div>

                  <!-- Order Form -->
                  <form class="space-y-6">
                      <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                          <div class="flex items-center gap-2">
                              <button 
                                  type="button"
                                  @click="quantity > 1 ? quantity-- : null"
                                  class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200"
                              >
                                  -
                              </button>
                              <input 
                                  type="number" 
                                  x-model="quantity"
                                  class="w-20 text-center border-gray-300 rounded-lg"
                                  min="1"
                              >
                              <button 
                                  type="button"
                                  @click="quantity++"
                                  class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200"
                              >
                                  +
                              </button>
                          </div>
                      </div>

                      <!-- Action Buttons -->
                      <div class="flex gap-4">
                          <button
                              type="button"
                              class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-colors"
                          >
                              Checkout Now
                          </button>
                          <button
                              type="button"
                              class="flex-1 bg-emerald-600 text-white py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-colors"
                          >
                              Add to Cart
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <!-- Include Alpine.js -->
  <script src="//unpkg.com/alpinejs" defer></script>
</x-layout>