<x-layout>
  <!-- CSS untuk styling dan animasi -->
  <style>
    .order-container {
      perspective: 1000px;
      margin-bottom: 30px;
    }
    
    .order-card {
      background: linear-gradient(135deg, #2c3e50, #34495e);
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      padding: 20px;
      transform-style: preserve-3d;
      transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .order-card:hover {
      transform: translateY(-5px) rotateX(5deg);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 10px;
    }
    
    .order-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .status-section {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .status-label {
      color: #bdc3c7;
      font-weight: 500;
    }
    
    .custom-select {
      position: relative;
      width: 220px;
    }
    
    .select-box {
      position: relative;
      width: 100%;
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      padding: 10px 15px;
      color: #fff;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      appearance: none;
    }
    
    .select-box:focus {
      border-color: #3498db;
      box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.3);
      outline: none;
    }
    
    .select-box option {
      background: #34495e;
      color: #fff;
    }
    
    /* Status indicators */
    .status-indicator {
      height: 40px;
      width: 100%;
      margin-top: 15px;
      display: flex;
      align-items: center;
      position: relative;
    }
    
    .status-step {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      position: relative;
      transition: all 0.4s ease;
    }
    
    .status-line {
      height: 4px;
      flex-grow: 1;
      background: rgba(255, 255, 255, 0.1);
      z-index: 5;
      position: relative;
      transition: all 0.4s ease;
    }
    
    .status-icon {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.5);
      transition: all 0.4s ease;
    }
    
    /* Status active states */
    .status-step.active {
      background: #2ecc71;
      transform: scale(1.2);
      box-shadow: 0 0 15px rgba(46, 204, 113, 0.5);
    }
    
    .status-step.active .status-icon {
      color: #fff;
    }
    
    .status-line.active {
      background: #2ecc71;
      box-shadow: 0 0 10px rgba(46, 204, 113, 0.3);
    }
    
    .status-step.error {
      background: #e74c3c;
      transform: scale(1.2);
      box-shadow: 0 0 15px rgba(231, 76, 60, 0.5);
    }
    
    /* Toast styling */
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: 8px;
      color: #fff;
      font-weight: 500;
      opacity: 0;
      transform: translateY(-20px);
      z-index: 1000;
      animation: slideIn 0.3s forwards, fadeOut 0.5s 2.5s forwards;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .toast-success {
      background: linear-gradient(135deg, #2ecc71, #27ae60);
      box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
    }
    
    .toast-error {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes fadeOut {
      from {
        opacity: 1;
      }
      to {
        opacity: 0;
      }
    }
    
    /* Loading animation */
    .loading-spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s linear infinite;
      margin-left: 10px;
    }
    
    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
    
    /* 3D truck animation */
    .truck-container {
      height: 70px;
      position: relative;
      margin-top: 20px;
      overflow: hidden;
    }
    
    .truck {
      position: absolute;
      width: 60px;
      height: 40px;
      transition: all 1s ease;
      transform-style: preserve-3d;
      transform: translateZ(0) rotateY(0deg);
    }
    
    .truck-body {
      position: absolute;
      width: 40px;
      height: 20px;
      background: #3498db;
      border-radius: 5px;
      bottom: 0;
      transform: translateZ(10px);
    }
    
    .truck-cabin {
      position: absolute;
      width: 20px;
      height: 15px;
      background: #2980b9;
      border-radius: 3px;
      bottom: 0;
      left: -20px;
      transform: translateZ(10px);
    }
    
    .truck-wheel {
      position: absolute;
      width: 10px;
      height: 10px;
      background: #222;
      border-radius: 50%;
      bottom: -5px;
      box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
      transform: translateZ(15px);
    }
    
    .wheel-front {
      left: -15px;
    }
    
    .wheel-back {
      right: 5px;
    }
    
    @keyframes moveTruck {
      0% {
        left: -60px;
        transform: translateZ(0) rotateY(0deg);
      }
      45% {
        left: 50%;
        transform: translateZ(0) rotateY(0deg);
      }
      50% {
        left: 50%;
        transform: translateZ(0) rotateY(180deg);
      }
      95% {
        left: -60px;
        transform: translateZ(0) rotateY(180deg);
      }
      100% {
        left: -60px;
        transform: translateZ(0) rotateY(0deg);
      }
    }
    
    .animate-truck {
      animation: moveTruck 4s infinite;
    }
    
    /* Packages animation */
    .package {
      position: absolute;
      width: 15px;
      height: 15px;
      background: #e67e22;
      border-radius: 3px;
      bottom: 10px;
      right: 10px;
      opacity: 0;
      transform: translateZ(20px);
      transition: all 0.5s ease;
    }
    
    @keyframes dropPackage {
      0% {
        transform: translateY(-50px) translateZ(20px);
        opacity: 0;
      }
      50% {
        transform: translateY(0) translateZ(20px);
        opacity: 1;
      }
      90% {
        transform: translateY(0) translateZ(20px);
        opacity: 1;
      }
      100% {
        transform: translateY(0) translateZ(20px);
        opacity: 0;
      }
    }
    
    .animate-package {
      animation: dropPackage 2s forwards;
    }
    
    /* Empty state styling */
    .empty-state-container {
      background: linear-gradient(135deg, #2c3e50, #34495e);
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      padding: 40px 20px;
      text-align: center;
      transform-style: preserve-3d;
      transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .empty-state-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .empty-state-icon {
      font-size: 60px;
      margin-bottom: 20px;
      display: block;
    }
    
    .empty-state-text {
      color: #ecf0f1;
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 15px;
    }
    
    .empty-state-subtext {
      color: #bdc3c7;
      font-size: 1rem;
      margin-bottom: 25px;
    }
    
    .empty-state-button {
      background: #3498db;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .empty-state-button:hover {
      background: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }
    
    .empty-state-animation {
      position: relative;
      height: 100px;
      width: 100%;
      margin: 30px 0;
    }
    
    .empty-state-truck {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80px;
      height: 50px;
      animation: floatTruck 3s ease-in-out infinite;
    }
    
    @keyframes floatTruck {
      0%, 100% {
        transform: translate(-50%, -50%);
      }
      50% {
        transform: translate(-50%, -60%);
      }
    }
  </style>

  <!-- Order Cards -->
  @if(count($orders) > 0)
    @foreach ($orders as $order)
    <div class="order-container">
      <div class="order-card" id="order-{{ $order->id }}">
        <div class="order-header">
          <span class="order-title">Order #{{ $order->id }}</span>
        </div>
        
        <div class="status-section">
          <span class="status-label">Status:</span>
          <div class="custom-select">
            <select
              id="status-{{ $order->id }}"
              class="select-box status-dropdown"
              data-order-id="{{ $order->id }}"
            >
              <option value="mencari_kurir" {{ $order->status_pengiriman == 'mencari_kurir' ? 'selected' : '' }}>Mencari Kurir</option>
              <option value="menunggu_pickup" {{ $order->status_pengiriman == 'menunggu_pickup' ? 'selected' : '' }}>Menunggu Pickup</option>
              <option value="sedang_diantar" {{ $order->status_pengiriman == 'sedang_diantar' ? 'selected' : '' }}>Sedang Diantar</option>
              <option value="terkirim" {{ $order->status_pengiriman == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
              <option value="gagal_kirim" {{ $order->status_pengiriman == 'gagal_kirim' ? 'selected' : '' }}>Gagal Kirim</option>
            </select>
            <div class="loading-spinner" id="spinner-{{ $order->id }}"></div>
          </div>
        </div>
        
        <!-- 3D Visualization -->
        <div class="status-indicator">
          <div class="status-step" data-status="mencari_kurir">
            <i class="status-icon">🔍</i>
          </div>
          <div class="status-line"></div>
          <div class="status-step" data-status="menunggu_pickup">
            <i class="status-icon">📦</i>
          </div>
          <div class="status-line"></div>
          <div class="status-step" data-status="sedang_diantar">
            <i class="status-icon">🚚</i>
          </div>
          <div class="status-line"></div>
          <div class="status-step" data-status="terkirim">
            <i class="status-icon">✓</i>
          </div>
        </div>
        
        <!-- 3D Truck Animation -->
        <div class="truck-container" id="truck-container-{{ $order->id }}">
          <div class="truck" id="truck-{{ $order->id }}">
            <div class="truck-cabin"></div>
            <div class="truck-body"></div>
            <div class="truck-wheel wheel-front"></div>
            <div class="truck-wheel wheel-back"></div>
          </div>
          <div class="package" id="package-{{ $order->id }}"></div>
        </div>
      </div>
    </div>
    @endforeach
  @else
    <div class="empty-state-container">
      <span class="empty-state-icon">📭</span>
      <h2 class="empty-state-text">Tidak Ada Pesanan</h2>
      <p class="empty-state-subtext">Saat ini tidak ada pesanan yang perlu dikelola</p>
      
      <div class="empty-state-animation">
        <div class="empty-state-truck">
          <div class="truck">
            <div class="truck-cabin"></div>
            <div class="truck-body"></div>
            <div class="truck-wheel wheel-front"></div>
            <div class="truck-wheel wheel-back"></div>
          </div>
        </div>
      </div>
      
      <button class="empty-state-button" onclick="window.location.reload()">Refresh Halaman</button>
    </div>
  @endif
@vite(['resources/js/style.js'])
  {{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
      const statusDropdowns = document.querySelectorAll('.status-dropdown');
      
      // Initialize status indicators
      statusDropdowns.forEach(dropdown => {
        updateStatusIndicators(dropdown.getAttribute('data-order-id'), dropdown.value);
        
        // Start animation if status is "sedang_diantar"
        if (dropdown.value === 'sedang_diantar') {
          const orderId = dropdown.getAttribute('data-order-id');
          document.getElementById(`truck-${orderId}`).classList.add('animate-truck');
        } else if (dropdown.value === 'terkirim') {
          const orderId = dropdown.getAttribute('data-order-id');
          document.getElementById(`package-${orderId}`).classList.add('animate-package');
        }
        
        dropdown.addEventListener('change', function () {
          const orderId = dropdown.getAttribute('data-order-id');
          const newStatus = dropdown.value;
          
          // Show loading spinner
          document.getElementById(`spinner-${orderId}`).style.display = 'block';
          
          // Update status in backend
          updateStatus(orderId, newStatus);
        });
      });
      
      function updateStatus(orderId, newStatus) {
        fetch(`/kurir/manajemen/${orderId}`, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ status_pengiriman: newStatus })
        })
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          // Hide spinner
          document.getElementById(`spinner-${orderId}`).style.display = 'none';
          
          if (data.success) {
            showToast('Status berhasil diupdate!', 'success');
            updateStatusIndicators(orderId, newStatus);
            
            // Handle animations based on new status
            const truck = document.getElementById(`truck-${orderId}`);
            const package = document.getElementById(`package-${orderId}`);
            
            // Reset animations first
            truck.classList.remove('animate-truck');
            package.classList.remove('animate-package');
            
            // Apply new animations based on status
            if (newStatus === 'sedang_diantar') {
              // Force reflow to restart animation
              void truck.offsetWidth;
              truck.classList.add('animate-truck');
            } else if (newStatus === 'terkirim') {
              void package.offsetWidth;
              package.classList.add('animate-package');
            }
            
          } else {
            showToast(data.message || 'Gagal update status!', 'error');
          }
        })
        .catch(error => {
          document.getElementById(`spinner-${orderId}`).style.display = 'none';
          console.error('Error:', error);
          showToast('Terjadi kesalahan saat update status.', 'error');
        });
      }
      
      function updateStatusIndicators(orderId, status) {
        const steps = document.querySelectorAll(`#order-${orderId} .status-step`);
        const lines = document.querySelectorAll(`#order-${orderId} .status-line`);
        
        // Reset all indicators
        steps.forEach(step => step.classList.remove('active', 'error'));
        lines.forEach(line => line.classList.remove('active'));
        
        // Set active steps based on current status
        const statusOrder = ['mencari_kurir', 'menunggu_pickup', 'sedang_diantar', 'terkirim'];
        
        if (status === 'gagal_kirim') {
          // Show error state
          steps[statusOrder.indexOf('sedang_diantar')].classList.add('error');
        } else {
          const currentIndex = statusOrder.indexOf(status);
          
          for (let i = 0; i <= currentIndex; i++) {
            const step = [...steps].find(s => s.dataset.status === statusOrder[i]);
            if (step) step.classList.add('active');
            
            // Activate line before this step if not the first step
            if (i > 0) {
              lines[i-1].classList.add('active');
            }
          }
        }
      }
      
      function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // Add icon based on type
        const icon = type === 'success' ? '✅' : '❌';
        toast.innerHTML = `<span>${icon}</span> ${message}`;
        
        document.body.appendChild(toast);
        
        // Remove after animation completes
        setTimeout(() => {
          toast.remove();
        }, 3000);
      }
    });
  </script> --}}
</x-layout>         