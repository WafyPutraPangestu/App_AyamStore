<x-layout>
    <div class="min-h-screen flex items-center justify-center p-6">
      <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="mb-6">
          <svg class="w-16 h-16 text-green-700 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Pesanan Berhasil</h2>
        
        <div class="p-4 bg-green-100 text-green-700 rounded-xl mb-6">
          Pembayaran Anda telah berhasil. Terima kasih telah berbelanja di toko kami!
        </div>
  
        <div class="mt-6">
          <a href="{{ route('user.katalog') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out inline-block">Kembali ke Daftar Pesanan</a>
        </div>
      </div>
    </div>
  </x-layout>