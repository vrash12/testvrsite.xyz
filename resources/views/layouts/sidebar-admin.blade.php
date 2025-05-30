@php
    $user = auth()->user();
@endphp

<aside class="w-64 min-h-screen bg-blue-700 text-white flex flex-col">
    {{-- Logo and Profile Section --}}
    <div class="flex flex-col items-center pt-6 pb-4">
        {{-- Logo --}}
        <div class="w-16 h-16 mb-4">
            <img src="{{ asset('images/patientcare-logo.svg') }}" alt="PatientCare" class="w-full h-full">
        </div>

        {{-- Profile Picture --}}
        <div class="w-24 h-24 rounded-full bg-white overflow-hidden mb-2">
            <img src="{{ $user->profile_picture ?? asset('images/default-avatar.png') }}"
                 class="object-cover w-full h-full" alt="Avatar">
        </div>

        {{-- Name and ID --}}
        <h3 class="text-center text-base font-semibold leading-tight px-2">
            {{ $user->name }}
        </h3>
        <p class="text-green-400 text-xs mt-1">Admissioner ID no. {{ $user->id }}</p>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 mt-6">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-600' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span>Home</span>
        </a>

        <a href="{{ route('admin.patients') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition mt-2 {{ request()->routeIs('admin.patients') ? 'bg-blue-800' : 'hover:bg-blue-600' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span>Patients</span>
        </a>

       <a href="{{ route('admin.admissions.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition mt-2 {{ request()->routeIs('admissions.create') ? 'bg-blue-800' : 'hover:bg-blue-600' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
            </svg>
            <span>Add Patient</span>
        </a>

        {{-- Logout at the bottom --}}
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-auto">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 w-full rounded-lg transition hover:bg-blue-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>

    {{-- Footer --}}
    <div class="text-center text-xs text-blue-200 py-2 mt-auto border-t border-blue-600">
        PatientCare © {{ date('Y') }}<br>
        Version 1.0.0
    </div>
</aside>