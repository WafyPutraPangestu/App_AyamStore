{{-- resources/views/kurir/profile.blade.php --}}
<x-layout>
  <x-notifications />
  @push('head')
    {{-- Iconify for icons --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
    {{-- Animate.css for optional title fade-in --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
      /* Minimal custom CSS */

      /* Style for the main card container - subtle shadow and border */
      .profile-card-container {
        background-color: white;
        border-radius: 1rem; /* rounded-xl */
        padding: 2rem; /* p-8 */
        border: 1px solid #e5e7eb; /* border-gray-200 */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); /* subtle shadow */
        transition: box-shadow 0.3s ease-in-out;
      }

      /* Optional: slightly lift the card on hover */
      /*
      .profile-card-container:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      }
      */

      /* Style for each profile item row - subtle hover background */
      .profile-item {
        display: flex;
        align-items: center;
        padding: 1rem; /* p-4 */
        border-radius: 0.75rem; /* rounded-lg */
        transition: background-color 0.2s ease-in-out;
        border-bottom: 1px solid #f3f4f6; /* border-b border-gray-100 */
      }

      .profile-item:last-child {
          border-bottom: none; /* Remove border for the last item */
      }

      .profile-item:hover {
        background-color: #f9fafb; /* bg-gray-50 */
      }

      /* Style for the icon */
      .item-icon {
        color: #4f46e5; /* text-indigo-600 */
        margin-right: 1rem; /* mr-4 */
        flex-shrink: 0; /* Prevent icon from shrinking */
        background-color: #eef2ff; /* bg-indigo-100 */
        padding: 0.5rem; /* p-2 */
        border-radius: 0.5rem; /* rounded-md */
        display: inline-flex; /* Align icon properly */
        align-items: center;
        justify-content: center;
      }

      /* Style for the data text */
      .profile-data {
        color: #374151; /* text-gray-700 */
        font-weight: 500; /* font-medium */
        flex-grow: 1; /* Allow text to take remaining space */
        word-break: break-word; /* Prevent long emails/names from overflowing */
      }

      /* Gradient Title Style */
      .gradient-title {
        background-image: linear-gradient(to right, #4f46e5, #a855f7); /* from-indigo-600 to-purple-500 */
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
      }

       /* Button Style (using Tailwind, but could define here for consistency) */
      .action-button {
        display: inline-block;
        padding: 0.75rem 1.5rem; /* px-6 py-3 */
        background-color: #4f46e5; /* bg-indigo-600 */
        color: white;
        font-weight: 600; /* font-semibold */
        border-radius: 0.5rem; /* rounded-lg */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        text-align: center;
      }

      .action-button:hover {
        background-color: #4338ca; /* hover:bg-indigo-700 */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* hover shadow */
      }

      /* Remove default focus outline, use Tailwind's focus rings if needed */
      *:focus {
        outline: none;
      }

    </style>
  @endpush

  <div class="max-w-lg mx-auto mt-10 mb-10 px-4"> {{-- Reduced max-width, added padding x --}}
    <div class="profile-card-container">
      <h1 class="text-3xl font-bold gradient-title mb-8 text-center animate__animated animate__fadeIn">
        Profil User
      </h1>

      <div class="space-y-2"> {{-- Reduced spacing between items --}}

        {{-- Item Nama --}}
        <div class="profile-item">
          <div class="item-icon">
            <iconify-icon icon="heroicons-outline:user" class="w-5 h-5"></iconify-icon> {{-- Slightly smaller icon --}}
          </div>
          <p class="profile-data">
            {{ auth()->user()->name }}
          </p>
        </div>

        {{-- Item Email --}}
        <div class="profile-item">
          <div class="item-icon">
            <iconify-icon icon="heroicons-outline:mail" class="w-5 h-5"></iconify-icon>
          </div>
          <p class="profile-data">
            {{ auth()->user()->email }}
          </p>
        </div>

        {{-- Item Telepon --}}
        <div class="profile-item">
          <div class="item-icon">
            <iconify-icon icon="heroicons-outline:phone" class="w-5 h-5"></iconify-icon>
          </div>
          <p class="profile-data">
            {{ auth()->user()->telepon ?: 'Nomor telepon belum diatur' }}
          </p>
        </div>

        {{-- Contoh Item Status (jika ada) --}}
        {{--
        @if(auth()->user()->status)
        <div class="profile-item">
          <div class="item-icon">
            <iconify-icon icon="heroicons-outline:tag" class="w-5 h-5"></iconify-icon>
          </div>
          <p class="profile-data">
            Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', auth()->user()->status)) }}</span>
          </p>
        </div>
        @endif
        --}}

      </div> {{-- End space-y-2 --}}

      {{-- Tombol Aksi --}}
      <div class="mt-8 text-center">
        <a href="{{ route('user.dashboard') }}" {{-- Ganti dengan route yang sesuai --}}
           class="action-button">
           Kembali ke Dashboard
        </a>
        {{-- Contoh Tombol Edit --}}
        {{--
        <a href="{{ route('kurir.profile.edit') }}"
           class="action-button ml-4" style="background-color: #10b981; /* bg-green-500 */">
          Edit Profil
        </a>
        --}}
        {{-- Note: Inline style for green button is used for example, better to create a new class or use Tailwind --}}
      </div>

    </div> {{-- End profile-card-container --}}
  </div> {{-- End max-w-lg --}}

</x-layout>