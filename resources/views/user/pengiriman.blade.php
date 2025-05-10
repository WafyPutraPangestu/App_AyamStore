<x-layout>
  <style>
    :root {
      --primary-color: #0052cc;
      --primary-light: #e6f0ff;
      --primary-dark: #003d99;
      --primary-gradient: linear-gradient(135deg, #0052cc, #0066ff);
      --success-color: #00875a;
      --success-light: #e3fcef;
      --light-gray: #f8f9fa;
      --border-color: #e9ecef;
      --text-dark: #172b4d;
      --text-muted: #6b778c;
    }
    
    .tracking-header {
      text-align: center;
      color: var(--primary-color);
      margin-bottom: 3rem;
      font-weight: 800;
      position: relative;
      padding-bottom: 1.5rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 2rem;
    }
    
    .tracking-header:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--primary-gradient);
      border-radius: 2px;
    }
    
    .tracking-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 82, 204, 0.15);
      padding: 2.5rem;
      margin-bottom: 2rem;
      max-width: 750px;
      margin: 0 auto 2rem;
      border-top: 5px solid var(--primary-color);
      position: relative;
      overflow: hidden;
    }
    
    .tracking-card:before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 150px;
      height: 150px;
      background: var(--primary-light);
      opacity: 0.3;
      border-radius: 0 0 0 100%;
      z-index: 0;
    }
    
    .tracking-status {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
      position: relative;
      z-index: 1;
    }
    
    .status-indicator {
      background: var(--primary-gradient);
      color: white;
      border-radius: 50px;
      padding: 0.7rem 2rem;
      text-transform: uppercase;
      font-weight: 700;
      font-size: 0.9rem;
      letter-spacing: 1px;
      box-shadow: 0 5px 15px rgba(0, 82, 204, 0.3);
    }
    
    .status-terkirim {
      background: linear-gradient(135deg, #00875a, #00b373);
      box-shadow: 0 5px 15px rgba(0, 135, 90, 0.3);
    }
    
    .tracking-info {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem;
      margin-top: 2.5rem;
      position: relative;
      z-index: 1;
    }
    
    .info-item {
      border-bottom: 2px solid var(--primary-light);
      padding-bottom: 1.2rem;
      transition: all 0.3s ease;
    }
    
    .info-item:hover {
      transform: translateY(-5px);
    }
    
    .info-label {
      color: var(--text-muted);
      font-size: 0.85rem;
      margin-bottom: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
    }
    
    .info-value {
      font-weight: 700;
      color: var(--text-dark);
      font-size: 1.2rem;
    }
    
    @media (max-width: 768px) {
      .tracking-info {
        grid-template-columns: 1fr;
      }
      
      .tracking-header {
        font-size: 1.5rem;
      }
    }
    
    #notif {
      display: none;
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #00875a, #00b373);
      color: white;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 15px 30px rgba(0, 135, 90, 0.25);
      text-align: center;
      width: 90%;
      max-width: 450px;
      z-index: 1000;
      animation: bounce 0.5s;
    }
    
    @keyframes bounce {
      0% { transform: translateX(-50%) scale(0.8); opacity: 0; }
      70% { transform: translateX(-50%) scale(1.05); }
      100% { transform: translateX(-50%) scale(1); opacity: 1; }
    }
    
    #notif p {
      font-size: 1.3rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }
    
    #notif strong {
      font-weight: 800;
      font-size: 1.4rem;
      display: block;
      margin-top: 5px;
    }
    
    #notif-ok {
      padding: 0.85rem 2.5rem;
      background: white;
      color: var(--success-color);
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-weight: 700;
      font-size: 1.1rem;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    #notif-ok:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
  </style>

  <h1 class="tracking-header">Tracking Pengiriman</h1>

  @if($order)
  <div id="tracking-container" class="tracking-card">
      <div class="tracking-status">
          <span class="status-indicator {{ $order->status_pengiriman === 'terkirim' ? 'status-terkirim' : '' }}" id="status-pengiriman">
              {{ strtoupper($order->status_pengiriman) }}
          </span>
      </div>
      
      <div class="tracking-info">
          <div class="info-item">
              <div class="info-label">Kurir</div>
              <div class="info-value">{{ $order->kurir?->name ?? 'Belum ada kurir' }}</div>
          </div>
          
          <div class="info-item">
              <div class="info-label">Ongkir</div>
              <div class="info-value">Rp {{ number_format($order->ongkir) }}</div>
          </div>
          
          <div class="info-item" style="grid-column: 1 / -1;">
              <div class="info-label">Alamat Pengiriman</div>
              <div class="info-value">{{ $order->alamat_pengiriman }}</div>
          </div>
          
          <div class="info-item" style="grid-column: 1 / -1;">
              <div class="info-label">Total Harga</div>
              <div class="info-value">Rp {{ number_format($order->total_harga) }}</div>
          </div>
      </div>
  </div>
  @else
      <div class="tracking-card" style="text-align: center;">
          <p style="font-size: 1.1rem; color: var(--text-muted);">Belum ada pesanan yang ditemukan.</p>
      </div>
  @endif

  {{-- notifikasi, awalnya disembunyikan --}}
  <div id="notif">
      <p>Produk Anda sudah</p>
      <strong>TERKIRIM!</strong>
      <button id="notif-ok">OK</button>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(function(){
      let orderId   = {{ $order->id }};
      let notified  = false;

      // polling tiap 10 detik
      let interval = setInterval(function(){
          $.getJSON(`{{ url('user/pengiriman') }}/${orderId}/status`, function(res){
              // Update text and class for status
              $('#status-pengiriman').text(res.status_pengiriman.toUpperCase());
              
              if (res.status_pengiriman === 'terkirim') {
                  $('#status-pengiriman').addClass('status-terkirim');
                  
                  if (!notified) {
                      notified = true;
                      $('#notif').fadeIn();
                  }
              } else {
                  $('#status-pengiriman').removeClass('status-terkirim');
              }
          });
      }, 10000);

      // saat user klik OK
      $('#notif-ok').click(function(){
          $('#notif').fadeOut();
          $('#tracking-container').slideUp();
          clearInterval(interval);
      });
  });
  </script>
</x-layout>