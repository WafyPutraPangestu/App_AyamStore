<x-layout>
  <div class="">
    <div class="flex flex-col justify-center items-center">
      <h1>Login</h1>
      <x-form method="POST" action="{{ route('auth.login') }}">
        @csrf
        <x-input name="email" label="" type="email" placeholder="Email" />
        <x-input name="password" label="" type="password" placeholder="Password" />
        <button type="submit">Login</button>
      </x-form>
    </div>
    <div class="flex flex-col justify-center items-center">
      <p>Belum punya akun? <a href="{{ route('auth.register') }}">Register</a></p>
      <p>Kembali Ke <a href="/">Home</a></p>
  </div>
</x-layout>