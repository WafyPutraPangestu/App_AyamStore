{{-- resources/views/kurir/profile.blade.php --}}
<x-layout>
  <x-notifications />
  @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
      .profile-card {
        perspective: 1000px;
        transform-style: preserve-3d;
      }
      
      .card-content {
        transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
      }
      
      .profile-card:hover .card-content {
        transform: translateY(-10px) rotateX(5deg);
        box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
      }
      
      .input-group {
        transform: translateZ(0);
        transition: all 0.3s ease;
      }
      
      .input-group:hover {
        transform: translateZ(20px);
      }
      
      .input-icon {
        transition: all 0.4s ease;
      }
      
      .input-group:hover .input-icon {
        transform: scale(1.2) rotate(5deg);
      }
      
      .form-input {
        border: 2px solid transparent;
        background-image: linear-gradient(white, white), 
                          linear-gradient(to right, #4f46e5, #818cf8);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        transition: all 0.3s ease;
      }
      
      .form-input:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
      }
      
      .status-badge-wrapper {
        overflow: hidden;
        position: relative;
      }
      
      .status-select {
        position: relative;
        z-index: 10;
        background: transparent;
        transition: all 0.3s ease;
      }
      
      .save-button {
        overflow: hidden;
        position: relative;
        transition: all 0.5s ease;
      }
      
      .save-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: all 0.6s ease;
      }
      
      .save-button:hover::before {
        left: 100%;
      }
      
      @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
      }
      
      .floating {
        animation: float 3s ease-in-out infinite;
      }
      
      .status-badge {
        position: relative;
        z-index: 5;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
      }
      
      .status-badge.tersedia {
        text-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
      }
      
      .status-badge.sedang_mengantar {
        text-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
      }
      
      .status-badge.tidak_aktif {
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
      }
    </style>
  @endpush

  <x-notifications />

  <div class="max-w-xl mx-auto mt-12 profile-card">
    <div class="card-content bg-white rounded-3xl p-8 border border-indigo-100">
      <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-500 mb-8 text-center animate__animated animate__fadeIn">Profil Kurir</h1>

      <form id="profile-form" method="POST" action="{{ route('kurir.profile.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="input-group flex items-center space-x-4 bg-indigo-50 p-4 rounded-2xl">
          <div class="input-icon bg-white p-3 rounded-xl shadow-md text-indigo-500">
            <iconify-icon icon="heroicons-outline:user" class="w-6 h-6"></iconify-icon>
          </div>
          <input type="text" value="{{ auth()->user()->name }}" disabled
                 class="form-input flex-1 bg-white p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
        </div>

        <div class="input-group flex items-center space-x-4 bg-indigo-50 p-4 rounded-2xl">
          <div class="input-icon bg-white p-3 rounded-xl shadow-md text-indigo-500">
            <iconify-icon icon="heroicons-outline:mail" class="w-6 h-6"></iconify-icon>
          </div>
          <input type="email" value="{{ auth()->user()->email }}" disabled
                 class="form-input flex-1 bg-white p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
        </div>

        <div class="input-group flex items-center space-x-4 bg-indigo-50 p-4 rounded-2xl">
          <div class="input-icon bg-white p-3 rounded-xl shadow-md text-indigo-500">
            <iconify-icon icon="heroicons-outline:phone" class="w-6 h-6"></iconify-icon>
          </div>
          <input id="telepon" name="telepon" type="text" value="{{ old('telepon', auth()->user()->telepon) }}"
                 placeholder="0812xxxx…" class="form-input flex-1 bg-white p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
        </div>

        <div class="input-group flex items-center space-x-4 bg-indigo-50 p-4 rounded-2xl">
          <div class="input-icon bg-white p-3 rounded-xl shadow-md text-indigo-500">
            <iconify-icon icon="heroicons-outline:truck" class="w-6 h-6"></iconify-icon>
          </div>
          <input id="kendaraan_info" name="kendaraan_info" type="text"
                 value="{{ old('kendaraan_info', $kurir->kendaraan_info) }}"
                 placeholder="Plat nomor, jenis kendaraan…" class="form-input flex-1 bg-white p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
        </div>

        <div class="input-group status-badge-wrapper flex items-center space-x-4 bg-indigo-50 p-4 rounded-2xl relative">
          <div class="input-icon bg-white p-3 rounded-xl shadow-md text-indigo-500">
            <iconify-icon icon="heroicons-outline:badge-check" class="w-6 h-6"></iconify-icon>
          </div>
          <select id="status-select" name="status"
                  class="status-select flex-1 bg-white p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            <option value="tersedia" {{ $kurir->status=='tersedia' ? 'selected':'' }}>Tersedia</option>
            <option value="sedang_mengantar" {{ $kurir->status=='sedang_mengantar' ? 'selected':'' }}>Sedang Mengantar</option>
            <option value="tidak_aktif" {{ $kurir->status=='tidak_aktif' ? 'selected':'' }}>Tidak Aktif</option>
          </select>
          <span id="status-badge" class="status-badge absolute top-4 right-4 px-4 py-1 rounded-full text-sm font-bold transition"></span>
        </div>

        <div class="text-center mt-10">
          <button type="submit"
                  class="save-button inline-flex items-center space-x-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1 transition duration-300">
            <iconify-icon icon="heroicons-outline:save" class="w-6 h-6 floating"></iconify-icon>
            <span class="font-bold">Simpan Perubahan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form    = document.getElementById('profile-form');
      const badge   = document.getElementById('status-badge');
      const select  = document.getElementById('status-select');
      const info    = document.getElementById('kendaraan_info');
      const telepon = document.getElementById('telepon');
      
      // Enhanced color mappings with animations
      const colors  = {
        tersedia: {
          classes: ['bg-green-100','text-green-800','animate-pulse'],
          styles: {
            boxShadow: '0 0 15px rgba(16, 185, 129, 0.5)',
            border: '2px solid #10B981'
          }
        },
        sedang_mengantar: {
          classes: ['bg-yellow-100','text-yellow-800'],
          styles: {
            boxShadow: '0 0 15px rgba(245, 158, 11, 0.5)',
            border: '2px solid #F59E0B'
          }
        },
        tidak_aktif: {
          classes: ['bg-red-100','text-red-800'],
          styles: {
            boxShadow: '0 0 15px rgba(239, 68, 68, 0.5)',
            border: '2px solid #EF4444'
          }
        }
      };

      function applyBadge(status) {
        // Reset classes and styles
        badge.className = 'status-badge absolute top-4 right-4 px-4 py-1 rounded-full text-sm font-bold transition';
        badge.style = '';
        
        // Apply text content with capitalization
        const formattedStatus = status.replace('_', ' ')
          .split(' ')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1))
          .join(' ');
        
        badge.textContent = formattedStatus;
        
        // Add classes
        colors[status].classes.forEach(c => badge.classList.add(c));
        badge.classList.add(status);
        
        // Apply styles
        Object.entries(colors[status].styles).forEach(([key, value]) => {
          badge.style[key] = value;
        });
        
        // Add entrance animation
        badge.classList.add('animate__animated', 'animate__bounceIn');
        
        // Remove animation class after it completes
        setTimeout(() => {
          badge.classList.remove('animate__animated', 'animate__bounceIn');
        }, 1000);
      }

      // Apply initial badge
      applyBadge(select.value);
      
      // Add change event listener to select
      select.addEventListener('change', () => {
        applyBadge(select.value);
      });

      // Add 3D tilt effect to the card
      const card = document.querySelector('.profile-card');
      card.addEventListener('mousemove', e => {
        const { left, top, width, height } = card.getBoundingClientRect();
        const x = (e.clientX - left) / width;
        const y = (e.clientY - top) / height;
        
        const tiltX = (y - 0.5) * 10;
        const tiltY = (0.5 - x) * 10;
        
        card.querySelector('.card-content').style.transform = 
          `translateY(-10px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
      });
      
      card.addEventListener('mouseleave', () => {
        card.querySelector('.card-content').style.transform = 
          'translateY(-10px) rotateX(5deg) rotateY(0deg)';
      });

      // Enhanced form submission with visual feedback
      form.addEventListener('submit', async e => {
        e.preventDefault();
        
        // Add loading state to button
        const button = form.querySelector('button[type="submit"]');
        const originalContent = button.innerHTML;
        button.innerHTML = `
          <div class="flex items-center space-x-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menyimpan...</span>
          </div>
        `;
        button.disabled = true;
        
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const payload = {
          telepon: telepon.value,
          kendaraan_info: info.value,
          status: select.value
        };

        try {
          const res = await fetch(form.action, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          const data = await res.json();

          if (data.success) {
            // Add success animation to the card
            const card = document.querySelector('.card-content');
            card.style.boxShadow = '0 0 30px rgba(16, 185, 129, 0.6)';
            setTimeout(() => {
              card.style.boxShadow = '';
            }, 1500);
            
            applyBadge(data.status);
            showToast('Berhasil', data.message, 'success');
          } else {
            // Add error animation
            const card = document.querySelector('.card-content');
            card.style.boxShadow = '0 0 30px rgba(239, 68, 68, 0.6)';
            setTimeout(() => {
              card.style.boxShadow = '';
            }, 1500);
            
            showToast('Gagal', data.message, 'error');
          }
        } catch (error) {
          showToast('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
          // Restore button state
          button.innerHTML = originalContent;
          button.disabled = false;
        }
      });
    });
  </script>
</x-layout>