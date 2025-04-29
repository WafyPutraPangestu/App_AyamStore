{{-- resources/views/kurir/pesanan-tersedia.blade.php --}}
<x-layout>
  @push('head')
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
      /* 3D Effects & Transitions */
      .order-card {
        perspective: 1000px;
        transform-style: preserve-3d;
        transition: all 0.5s ease;
      }
      
      .order-card-inner {
        transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 25px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.8);
        transform-style: preserve-3d;
      }
      
      .order-card:hover .order-card-inner {
        transform: translateY(-8px) rotateX(4deg);
        box-shadow: 0 20px 30px rgba(79, 70, 229, 0.15), 0 10px 20px rgba(0, 0, 0, 0.08);
        border-color: rgba(99, 102, 241, 0.2);
      }
      
      /* Checkbox Animation */
      .fancy-checkbox {
        position: relative;
        width: 1.5rem;
        height: 1.5rem;
        appearance: none;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
      }
      
      .fancy-checkbox:hover {
        border-color: #818cf8;
        transform: scale(1.05);
      }
      
      .fancy-checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
      }
      
      .fancy-checkbox:checked::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0.5rem;
        height: 0.9rem;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: translate(-50%, -60%) rotate(45deg);
      }
      
      .fancy-checkbox:checked + label {
        color: #4f46e5;
        font-weight: 700;
      }
      
      /* Glowing Button */
      .submit-button {
        position: relative;
        overflow: hidden;
        background: linear-gradient(90deg, #4f46e5, #818cf8);
        transition: all 0.4s ease;
      }
      
      .submit-button::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.5s ease;
        opacity: 0;
      }
      
      .submit-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
      }
      
      .submit-button:hover::before {
        animation: glitter 1.5s infinite;
        opacity: 1;
      }
      
      @keyframes glitter {
        0% {
          left: -50%;
        }
        100% {
          left: 150%;
        }
      }
      
      /* Pulse Animation */
      @keyframes pulse-border {
        0% {
          box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
        }
        70% {
          box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
        }
      }
      
      /* Order Card Elements */
      .order-header {
        position: relative;
        z-index: 10;
        transition: all 0.3s ease;
      }
      
      .order-card:hover .order-header {
        transform: translateZ(30px);
      }
      
      .order-detail {
        transition: all 0.4s ease;
        transform: translateZ(0);
      }
      
      .order-card:hover .order-detail {
        transform: translateZ(20px);
      }
      
      .order-items {
        transition: all 0.5s ease;
        transform: translateZ(0);
      }
      
      .order-card:hover .order-items {
        transform: translateZ(10px);
      }
      
      /* Item List */
      .item-list {
        position: relative;
        margin-left: 1.5rem;
      }
      
      .item-list::before {
        content: '';
        position: absolute;
        top: 0.6rem;
        left: -1.5rem;
        width: 0.5rem;
        height: 0.5rem;
        background: #4f46e5;
        border-radius: 50%;
        transform: scale(0);
        transition: transform 0.3s ease;
      }
      
      .order-card:hover .item-list::before {
        transform: scale(1);
      }
      
      /* Alert Styles */
      .alert {
        animation: slide-in 0.5s ease-out;
        position: relative;
        overflow: hidden;
      }
      
      .alert::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
      }
      
      @keyframes slide-in {
        0% {
          transform: translateY(-20px);
          opacity: 0;
        }
        100% {
          transform: translateY(0);
          opacity: 1;
        }
      }
      
      /* Empty State */
      .empty-state {
        transition: all 0.5s ease;
      }
      
      .empty-state:hover {
        transform: scale(1.05);
      }
      
      /* Pagination */
      .pagination-item {
        transition: all 0.3s ease;
      }
      
      .pagination-item:hover {
        transform: translateY(-3px);
      }
    </style>
  @endpush

  <div class="max-w-3xl mx-auto mt-12 px-4">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-500 mb-8 animate__animated animate__fadeIn">
      <iconify-icon icon="heroicons-outline:clipboard-list" class="inline-block mr-2 w-8 h-8"></iconify-icon>
      Pesanan Tersedia
    </h1>

    @if(session('error'))
      <div class="alert mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md animate__animated animate__fadeIn">
        <div class="flex items-center">
          <iconify-icon icon="heroicons-outline:exclamation-circle" class="w-6 h-6 mr-3 text-red-500"></iconify-icon>
          <p>{{ session('error') }}</p>
        </div>
      </div>
    @endif

    @if(session('success'))
      <div class="alert mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md animate__animated animate__fadeIn">
        <div class="flex items-center">
          <iconify-icon icon="heroicons-outline:check-circle" class="w-6 h-6 mr-3 text-green-500"></iconify-icon>
          <p>{{ session('success') }}</p>
        </div>
      </div>
    @endif

    <form id="orders-form" action="{{ route('kurir.ambil-tugas') }}" method="POST" class="space-y-6">
      @csrf

      @forelse($availableOrders as $order)
        <div class="order-card mb-6">
          <div class="order-card-inner bg-white rounded-2xl p-6">
            <div class="order-header flex items-center mb-4 pb-3 border-b border-gray-100">
              <input
                type="checkbox"
                name="selected_orders[]"
                value="{{ $order->id }}"
                id="order-{{ $order->id }}"
                class="fancy-checkbox"
              />
              <label for="order-{{ $order->id }}" class="ml-3 font-bold text-indigo-800 text-lg cursor-pointer">
                Pesanan #{{ $order->id }}
              </label>
            </div>

            <div class="order-detail space-y-3 mb-4">
              <p class="flex items-center text-gray-700">
                <iconify-icon icon="heroicons-outline:calendar" class="w-5 h-5 mr-2 text-indigo-500"></iconify-icon>
                <span class="font-medium">Tanggal:</span>
                <span class="ml-2">{{ $order->created_at->format('d M Y H:i') }}</span>
              </p>
              
              <p class="flex items-start text-gray-700">
                <iconify-icon icon="heroicons-outline:location-marker" class="w-5 h-5 mr-2 text-indigo-500 mt-1"></iconify-icon>
                <span class="font-medium">Alamat:</span>
                <span class="ml-2">{{ $order->alamat_pengiriman }}</span>
              </p>
            </div>
            
            <div class="order-items">
              <h4 class="flex items-center font-semibold text-gray-800 mb-3">
                <iconify-icon icon="heroicons-outline:shopping-bag" class="w-5 h-5 mr-2 text-indigo-500"></iconify-icon>
                Item Pesanan:
              </h4>
              
              @if($order->items->isNotEmpty())
                <ul class="space-y-2 pl-7">
                  @foreach($order->items as $item)
                    <li class="item-list text-gray-700">
                      <span class="font-medium">{{ $item->produk->nama_produk ?? 'Produk #'.$item->produk_id }}</span>
                      <span class="ml-2 px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">× {{ $item->quantity }}</span>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="text-gray-500 italic pl-7">Tidak ada detail item.</p>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="empty-state py-16 px-6 bg-white rounded-2xl shadow text-center">
          <iconify-icon icon="heroicons-outline:inbox" class="w-16 h-16 mx-auto text-indigo-300 mb-4"></iconify-icon>
          <p class="text-xl text-gray-600">Tidak ada pesanan yang siap diambil.</p>
          <p class="text-gray-500 mt-2">Coba periksa kembali nanti.</p>
        </div>
      @endforelse

      @if($availableOrders->isNotEmpty())
        <div id="order-counter" class="bg-indigo-50 p-4 rounded-xl text-indigo-700 mb-4 hidden">
          <p class="font-medium"><span id="selected-count">0</span> pesanan dipilih</p>
        </div>
        
        <div class="flex justify-end">
          <button
            type="submit"
            id="submit-button"
            class="submit-button inline-flex items-center px-8 py-4 text-white font-bold rounded-xl shadow relative overflow-hidden transform transition disabled:opacity-50 disabled:cursor-not-allowed"
            disabled
          >
            <iconify-icon icon="heroicons-outline:truck" class="w-6 h-6 mr-2"></iconify-icon>
            <span>Ambil Pesanan Terpilih</span>
          </button>
        </div>
        
        <div class="mt-8">
          <div class="pagination flex justify-center">
            {{ $availableOrders->links() }}
          </div>
        </div>
      @endif
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const checkboxes = document.querySelectorAll('.fancy-checkbox');
      const submitButton = document.getElementById('submit-button');
      const orderCounter = document.getElementById('order-counter');
      const selectedCount = document.getElementById('selected-count');
      
      function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.fancy-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
          submitButton.disabled = false;
          orderCounter.classList.remove('hidden');
          orderCounter.classList.add('animate__animated', 'animate__fadeIn');
        } else {
          submitButton.disabled = true;
          orderCounter.classList.add('hidden');
        }
      }
      
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
          updateSelectedCount();
          
          // Add pulse animation to the card when checked
          const card = checkbox.closest('.order-card-inner');
          
          if (checkbox.checked) {
            card.style.borderColor = '#4f46e5';
            card.style.animation = 'pulse-border 2s infinite';
          } else {
            card.style.borderColor = '';
            card.style.animation = '';
          }
        });
      });
      
      // Add 3D tilt effect to the cards
      const orderCards = document.querySelectorAll('.order-card');
      
      orderCards.forEach(card => {
        card.addEventListener('mousemove', e => {
          const cardRect = card.getBoundingClientRect();
          const cardInner = card.querySelector('.order-card-inner');
          
          const x = e.clientX - cardRect.left;
          const y = e.clientY - cardRect.top;
          
          const centerX = cardRect.width / 2;
          const centerY = cardRect.height / 2;
          
          const rotateY = (x - centerX) / 20;
          const rotateX = (centerY - y) / 20;
          
          cardInner.style.transform = `translateY(-8px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
        
        card.addEventListener('mouseleave', () => {
          const cardInner = card.querySelector('.order-card-inner');
          cardInner.style.transform = '';
        });
      });
      
      // Form submission animation
      const form = document.getElementById('orders-form');
      
      if (form) {
        form.addEventListener('submit', e => {
          const checkedBoxes = document.querySelectorAll('.fancy-checkbox:checked');
          
          if (checkedBoxes.length === 0) {
            e.preventDefault();
            return;
          }
          
          submitButton.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          `;
        });
      }
    });
  </script>
</x-layout>