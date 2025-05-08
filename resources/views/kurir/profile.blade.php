<!-- resources/views/kurir/profile.blade.php -->
<x-layout>
  <x-notifications />
  @push('head')
    <style>
      /* (Include the same CSS from before) */
      .profile-card { perspective: 1000px; transform-style: preserve-3d; }
      .card-content { transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 15px 35px rgba(50,50,93,0.1), 0 5px 15px rgba(0,0,0,0.07); }
      .profile-card:hover .card-content { transform: translateY(-10px) rotateX(5deg); box-shadow: 0 20px 40px rgba(50,50,93,0.15), 0 10px 20px rgba(0,0,0,0.1); }
      .info-group { display: flex; align-items: center; gap: 1rem; background: #EEF2FF; padding: 1rem; border-radius: 1rem; }
      .info-icon { background: white; padding: .75rem; border-radius: .75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); color: #6366F1; }
      .info-text { flex: 1; background: white; padding: 1rem; border-radius: .75rem; border: 2px solid transparent; }
      .status-badge { position: relative; padding: .5rem 1rem; border-radius: 9999px; font-weight: bold; text-transform: capitalize; }
      .tersedia { background: #D1FAE5; color: #047857; box-shadow: 0 0 15px rgba(16,185,129,0.5); }
      .sedang_mengantar { background: #FEF3C7; color: #B45309; box-shadow: 0 0 15px rgba(245,158,11,0.5); }
      .tidak_aktif { background: #FEE2E2; color: #B91C1C; box-shadow: 0 0 15px rgba(239,68,68,0.5); }
    </style>
  @endpush

  <div class="max-w-xl mx-auto mt-12 profile-card">
    <div class="card-content bg-white rounded-3xl p-8 border border-indigo-100">
      <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-500 mb-8 text-center">Profil Kurir</h1>

      <div class="space-y-6">
        <div class="info-group">
          <div class="info-icon">
            <iconify-icon icon="heroicons-outline:user" class="w-6 h-6"></iconify-icon>
          </div>
          <div class="info-text">{{ auth()->user()->name }}</div>
        </div>

        <div class="info-group">
          <div class="info-icon">
            <iconify-icon icon="heroicons-outline:mail" class="w-6 h-6"></iconify-icon>
          </div>
          <div class="info-text">{{ auth()->user()->email }}</div>
        </div>

        <div class="info-group">
          <div class="info-icon">
            <iconify-icon icon="heroicons-outline:phone" class="w-6 h-6"></iconify-icon>
          </div>
          <div class="info-text">{{ $kurir->telepon }}</div>
        </div>

        <div class="info-group">
          <div class="info-icon">
            <iconify-icon icon="heroicons-outline:truck" class="w-6 h-6"></iconify-icon>
          </div>
          <div class="info-text">{{ $kurir->kendaraan_info }}</div>
        </div>

        <div class="info-group">
          <div class="info-icon">
            <iconify-icon icon="heroicons-outline:badge-check" class="w-6 h-6"></iconify-icon>
          </div>
          <span class="status-badge {{ $kurir->status }}">{{ ucfirst(str_replace('_', ' ', $kurir->status)) }}</span>
        </div>
      </div>
    </div>
  </div>
</x-layout>
