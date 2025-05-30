@extends('layouts.guest')

@section('title','Welcome to PatientCare')

@section('content')
  <section class="relative h-screen overflow-hidden">
    {{-- fullscreen background --}}
    <div class="absolute inset-0">
      <img
        src="{{ asset('images/hospital-bg.jpg') }}"
        alt="Hospital background"
        class="w-full h-full object-cover filter brightness-75"
      />
    </div>

    {{-- hero content --}}
    <div class="relative z-10 flex flex-col items-start justify-center h-full max-w-2xl px-6 lg:px-12">
      <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">
        Welcome to PatientCare
      </h1>
      <p class="text-lg text-white mb-6">
        PatientCare Portal gives you complete access to your healthcare information, billing, and services in one secure place.
      </p>
      <a href="#services"
         class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition">
        Read More
        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>
  </section>

  {{-- Sections you might link to --}}
  <section id="services" class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-6">Our Services</h2>
      {{-- ... --}}
    </div>
  </section>

  <section id="contact" class="py-20">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-6">Contact Us</h2>
      {{-- ... --}}
    </div>
  </section>
@endsection
