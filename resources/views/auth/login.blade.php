{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('content')
<div class="min-h-screen relative bg-cover bg-center" style="background-image: url('{{ asset('images/hospital-hallway.jpg') }}');">
  {{-- White translucent overlay --}}
  <div class="absolute inset-0 bg-white bg-opacity-60"></div>

  <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-full max-w-md">
      {{-- Logo --}}
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/patientcare-logo.svg') }}" alt="PatientCare" class="h-12">
      </div>

      <h2 class="text-2xl font-semibold text-center mb-6">BILLING PORTAL</h2>

      <form method="POST" action="{{ route('admin.authenticate') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
          <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
          <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
            :value="old('email')"
            required
            autofocus
          />
          <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div class="mb-4 relative">
          <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
          <x-text-input
            id="password"
            name="password"
            type="password"
            class="mt-1 block w-full pr-10 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required
            autocomplete="current-password"
          />
          {{-- Eye toggle --}}
          <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19
                       c-4.477 0-8.268-2.943-9.542-7
                       a9.958 9.958 0 012.473-3.554
                       m3.864-2.507A9.956 9.956 0 0112 5
                       c4.477 0 8.268 2.943 9.542 7
                       a9.986 9.986 0 01-4.415 5.059
                       M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18" />
            </svg>
          </button>
          <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Forgot Password --}}
        <div class="text-right mb-6">
          @if (Route::has('admin.password.request'))
            <a href="{{ route('admin.password.request') }}"
               class="text-sm text-blue-600 hover:underline">
              {{ __('Forgot your password?') }}
            </a>
          @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
          {{ __('Log in') }}
        </button>
      </form>
    </div>
  </div>

  {{-- Footer --}}
  <div class="absolute bottom-4 left-4 text-sm text-gray-600 z-10">
    PatientCare © {{ date('Y') }} Version 1.0.0
  </div>
</div>

{{-- Password toggle script --}}
@push('scripts')
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput  = document.querySelector('#password');
  const eyeIcon        = document.querySelector('#eyeIcon');
  const eyeOffIcon     = document.querySelector('#eyeOffIcon');

  togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    eyeIcon.classList.toggle('hidden');
    eyeOffIcon.classList.toggle('hidden');
  });
</script>
@endpush
