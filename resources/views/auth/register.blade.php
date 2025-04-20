<x-layout>
  <div class="">
  <div class="flex flex-col justify-center items-center">
    <h1>Register</h1>
    <x-form method="POST" action="{{ route('auth.register') }}">
      <x-input name="name" label="" type="text" placeholder="Name" />
      <x-input name="email" label="" type="email" placeholder="Email" />
      <x-input name="password" label="" type="password" placeholder="Password" />
      <x-input name="password_confirmation" label="" type="password" placeholder="Password Confirmation" />
      <button type="submit">Register</button>
      <p>Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-blue-500">Login</a></p>

    </x-form>
  </div>
</div>
</x-layout>