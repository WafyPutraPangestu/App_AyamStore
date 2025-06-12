<x-layout>

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
                <option value="menunggu_pickup" {{ $order->status_pengiriman == 'menunggu_pickup' ? 'selected' : '' }}>Menunggu Pickup</option>
                <option value="sedang_diantar" {{ $order->status_pengiriman == 'sedang_diantar' ? 'selected' : '' }}>Sedang Diantar</option>
                <option value="terkirim" {{ $order->status_pengiriman == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                <option value="gagal_kirim" {{ $order->status_pengiriman == 'gagal_kirim' ? 'selected' : '' }}>Gagal Kirim</option>
              </select>
              <div class="loading-spinner" id="spinner-{{ $order->id }}"></div>
            </div>
          </div>
    
          {{-- Upload Bukti Pengiriman --}}
          <div class="bukti-pengiriman-section" id="bukti-section-{{ $order->id }}" style="display: none; margin-top: 10px;">
            <label for="bukti-{{ $order->id }}">Upload Bukti Pengiriman:</label>
            <input type="file" id="bukti-{{ $order->id }}" class="bukti-file" data-order-id="{{ $order->id }}" accept="image/*" />
          </div>
    
          {{-- Status Indicator --}}
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const statusDropdowns = document.querySelectorAll('.status-dropdown');
    
      statusDropdowns.forEach(dropdown => {
        const orderId = dropdown.getAttribute('data-order-id');
        updateStatusIndicators(orderId, dropdown.value);
    
        if (dropdown.value === 'sedang_diantar') {
          document.getElementById(`truck-${orderId}`).classList.add('animate-truck');
        } else if (dropdown.value === 'terkirim') {
          document.getElementById(`package-${orderId}`).classList.add('animate-package');
        }
    
        toggleUploadSection(orderId, dropdown.value);
    
        dropdown.addEventListener('change', function () {
          const newStatus = dropdown.value;
          toggleUploadSection(orderId, newStatus);
    
          if (newStatus === 'terkirim') {
            const fileInput = document.getElementById(`bukti-${orderId}`);
            if (!fileInput.files.length) {
              showToast('Silakan upload bukti pengiriman sebelum mengubah ke status "terkirim"', 'error');
              dropdown.value = 'sedang_diantar';
              toggleUploadSection(orderId, 'sedang_diantar');
              return;
            }
          }
    
          document.getElementById(`spinner-${orderId}`).style.display = 'block';
          updateStatus(orderId, newStatus);
        });
      });
    
      // --- PERUBAIKAN 1: Logika menampilkan tombol upload ---
      function toggleUploadSection(orderId, status) {
        const uploadSection = document.getElementById(`bukti-section-${orderId}`);
        // Tampilkan jika status 'sedang_diantar' ATAU 'terkirim'
        if (status === 'sedang_diantar' || status === 'terkirim') {
          uploadSection.style.display = 'block';
        } else {
          uploadSection.style.display = 'none';
        }
      }
    
      function updateStatus(orderId, newStatus) {
        const fileInput = document.getElementById(`bukti-${orderId}`);
        const formData = new FormData();
  
        // --- PERUBAIKAN 2: Method Spoofing untuk request PUT ---
        formData.append('_method', 'PATCH');
  
        formData.append('status_pengiriman', newStatus);
        if (fileInput && fileInput.files.length > 0) {
          formData.append('bukti_pengiriman', fileInput.files[0]);
        }
    
        fetch(`/kurir/manajemen/${orderId}`, {
          method: 'POST', // Method tetap POST karena membawa FormData
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          document.getElementById(`spinner-${orderId}`).style.display = 'none';
          if (!response.ok) {
            // Mengambil pesan error dari response JSON jika ada
            return response.json().then(err => {
                throw new Error(err.message || 'Gagal menyimpan perubahan');
            });
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            showToast('Status berhasil diupdate!', 'success');
            updateStatusIndicators(orderId, newStatus);
    
            const truck = document.getElementById(`truck-${orderId}`);
            const pkg = document.getElementById(`package-${orderId}`);
            truck.classList.remove('animate-truck');
            pkg.classList.remove('animate-package');
    
            if (newStatus === 'sedang_diantar') {
              void truck.offsetWidth;
              truck.classList.add('animate-truck');
            } else if (newStatus === 'terkirim') {
              void pkg.offsetWidth;
              pkg.classList.add('animate-package');
            }
          } else {
            showToast(data.message || 'Terjadi kesalahan.', 'error');
          }
        })
        .catch(error => {
          document.getElementById(`spinner-${orderId}`).style.display = 'none';
          console.error('Error:', error);
          showToast(error.message || 'Terjadi kesalahan saat mengupdate.', 'error');
        });
      }
    
      function updateStatusIndicators(orderId, status) {
        const steps = document.querySelectorAll(`#order-${orderId} .status-step`);
        const lines = document.querySelectorAll(`#order-${orderId} .status-line`);
        steps.forEach(step => step.classList.remove('active', 'error'));
        lines.forEach(line => line.classList.remove('active'));
    
        const statusOrder = ['mencari_kurir', 'menunggu_pickup', 'sedang_diantar', 'terkirim'];
    
        if (status === 'gagal_kirim') {
          const sedandDiantarStep = [...steps].find(s => s.dataset.status === 'sedang_diantar');
          if (sedandDiantarStep) sedandDiantarStep.classList.add('error');
        } else {
          const currentIndex = statusOrder.indexOf(status);
          for (let i = 0; i <= currentIndex; i++) {
            const step = [...steps].find(s => s.dataset.status === statusOrder[i]);
            if (step) step.classList.add('active');
            if (i > 0 && lines[i-1]) lines[i-1].classList.add('active');
          }
        }
      }
    
      function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icon = type === 'success' ? '✅' : '❌';
        toast.innerHTML = `<span>${icon}</span> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
      }
    });
    </script>
    
  </x-layout>