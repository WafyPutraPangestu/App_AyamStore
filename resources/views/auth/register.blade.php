<x-layout>
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
      <div class="px-8 pt-8 pb-6 bg-blue-500 text-white">
        <h1 class="text-3xl font-bold text-center">Register</h1>
      </div>
      <div class="p-8">
        <x-form method="POST" action="{{ route('auth.register') }}" class="space-y-6">
          @csrf
          <div class="space-y-4">
            <x-input name="name" label="" type="text" placeholder="Name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <x-input name="telepon" label="" type="number" placeholder="No Telephone" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <x-input name="email" label="" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <x-input name="password" label="" type="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <x-input name="password_confirmation" label="" type="password" placeholder="Password Confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
          </div>
          <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">Register</button>
          <p class="mt-4 text-center text-gray-600">Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Login</a></p>
        </x-form>
      </div>
    </div>
  </div>
</x-layout>