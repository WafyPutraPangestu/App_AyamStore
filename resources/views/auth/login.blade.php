<x-layout>
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
      <div class="px-8 pt-8 pb-6 bg-blue-500 text-white">
        <h1 class="text-3xl font-bold text-center">Login</h1>
      </div>
      <div class="p-8">
        <x-form method="POST" action="{{ route('auth.login') }}" class="space-y-6">
          @csrf
          <div class="space-y-4">
            <x-input name="email" label="" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <x-input name="password" label="" type="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
          </div>
          <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">Login</button>
        </x-form>
        <div class="mt-6 text-center">
          <p class="text-gray-600">Belum punya akun? <a href="{{ route('auth.register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Register</a></p>
          <p class="mt-2 text-gray-600">Kembali Ke <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">Home</a></p>
        </div>
      </div>
    </div>
  </div>
</x-layout>