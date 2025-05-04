{{-- resources/views/kurir/profile.blade.php --}}
<x-layout>
  <x-notifications />
  @push('head')
    {{-- Iconify for icons --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
    {{-- Animate.css for optional title fade-in --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  
  @endpush

  <div class="mb-16 bg-gray-50 rounded-2xl p-8 shadow-md" id="profile">
    <h2 class="text-3xl font-bold mb-8 text-center">Profil Saya</h2>
    
    <div class="grid md:grid-cols-2 gap-8 items-center">
        <div class="flex justify-center">
            <div class="relative">
                <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-yellow-400 shadow-lg">
                    <!-- Placeholder avatar -->
                    <div class="bg-yellow-100 w-full h-full flex items-center justify-center text-6xl text-yellow-500 font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 bg-yellow-500 text-white p-2 rounded-full shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-sm text-gray-500">Nama</p>
                <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold text-lg">{{ auth()->user()->email }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-sm text-gray-500">Role</p>
                <p class="font-semibold text-lg">{{ auth()->user()->role }}</p>
            </div>
            
            <div class="mt-6">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 mr-2">
                    Edit Profil
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-300">
                    Ubah Password
                </button>
            </div>
        </div>
    </div>
</div>
{{-- dadadad --}}
<div class="mb-16">
  <h2 class="text-3xl font-bold mb-8 text-center relative overflow-hidden">
      <span class="relative z-10 bg-white px-6">Riwayat Pembelian</span>
      <div class="absolute top-1/2 left-0 w-full h-px bg-gray-300"></div>
  </h2>
  
  <div x-data="{ activeTab: 'semua' }" class="space-y-6">
      <!-- Tabs -->
      <div class="flex flex-wrap justify-center space-x-2 mb-6">
          <button @click="activeTab = 'semua'" 
                  :class="{ 'bg-yellow-500 text-white': activeTab === 'semua', 'bg-gray-200 text-gray-700': activeTab !== 'semua' }"
                  class="px-4 py-2 rounded-full font-medium transition duration-300">
              Semua
          </button>
          <button @click="activeTab = 'diproses'" 
                  :class="{ 'bg-yellow-500 text-white': activeTab === 'diproses', 'bg-gray-200 text-gray-700': activeTab !== 'diproses' }"
                  class="px-4 py-2 rounded-full font-medium transition duration-300">
              Diproses
          </button>
          <button @click="activeTab = 'dikirim'" 
                  :class="{ 'bg-yellow-500 text-white': activeTab === 'dikirim', 'bg-gray-200 text-gray-700': activeTab !== 'dikirim' }"
                  class="px-4 py-2 rounded-full font-medium transition duration-300">
              Dikirim
          </button>
          <button @click="activeTab = 'selesai'" 
                  :class="{ 'bg-yellow-500 text-white': activeTab === 'selesai', 'bg-gray-200 text-gray-700': activeTab !== 'selesai' }"
                  class="px-4 py-2 rounded-full font-medium transition duration-300">
              Selesai
          </button>
      </div>
      <div>
        <!-- Empty State - For Demo -->
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada pesanan</h3>
            <p class="text-gray-500 mb-6">Anda belum melakukan pembelian apa pun.</p>
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Mulai Belanja
            </button>
        </div>
    </div>
</x-layout>