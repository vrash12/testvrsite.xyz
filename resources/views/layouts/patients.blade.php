{{-- resources/views/layouts/patients.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Patient Panel • PatientCare</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Font Awesome --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    .sidebar {
      width: 240px;
      display: flex;
      flex-direction: column;
      background-color: #00529A;
    }
    .logo {
      width: 80px;
    }
    .avatar {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border: 3px solid #fff;
      border-radius: 50%;
    }
    .nav-link {
      transition: background-color 0.2s ease;
      border-radius: 0.375rem;
    }
    .nav-link:hover {
      background-color: rgba(255,255,255,0.2);
      color: #fff !important;
    }
    .icon {
      width: 30px;
    }
  </style>
</head>

<body>
@php
  $user    = Auth::user();
  $patient = $user->patient;
@endphp

<div class="d-flex vh-100">

  {{-- Sidebar --}}
  <aside class="sidebar text-white p-3 flex-shrink-0">

    {{-- Logo --}}
    <div class="text-center mb-4">
      <img src="{{ asset('images/patientcare-logo-white.png') }}"
           alt="PatientCare Logo"
           class="logo img-fluid">
    </div>

    {{-- Avatar + Name + ID --}}
    <div class="text-center mb-5">
      @if($patient && $patient->profile_photo)
        <img src="{{ asset('storage/patient/images/'.$patient->profile_photo) }}"
             alt="Avatar"
             class="avatar d-block mx-auto mb-2">
      @else
        <div class="avatar bg-light d-block mx-auto mb-2"></div>
      @endif

      {{-- Username --}}
      <div class="fw-bold text-white">{{ $user->username }}</div>

      {{-- Patient full name (optional) --}}
      @if($patient)
        <div class="small">{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</div>
        <div class="small">ID: {{ str_pad($patient->patient_id, 8, '0', STR_PAD_LEFT) }}</div>
      @else
        <div class="small">ID: —</div>
      @endif
    </div>

    {{-- Navigation --}}
    <nav class="mb-auto">
      <a href="{{ route('patient.dashboard') }}"
         class="nav-link d-flex align-items-center px-2 py-2 text-white">
        <i class="fas fa-home fa-lg icon"></i>
        <span class="ms-2">Home</span>
      </a>

      <a href="{{ route('patient.account') }}"
         class="nav-link d-flex align-items-center px-2 py-2 text-white">
        <i class="fas fa-user-circle fa-lg icon"></i>
        <span class="ms-2">My Account</span>
      </a>

     <a href="{{ route('patient.billing') }}"
     class="nav-link d-flex align-items-center px-2 py-2 text-white">
  <i class="fa-solid fa-file-invoice-dollar fa-lg icon"></i>
        <span class="ms-2">Billing</span>
      </a>

      <a href="{{ route('patient.notification') }}" class="nav-link d-flex align-items-center px-2 py-2 text-white">
      <i class="fa-solid fa-bell fa-lg icon"></i>
        <span class="ms-2">Notifications</span>
      </a>
    </nav>

    {{-- Logout & Footer --}}
    <div class="mt-auto pt-4 text-center">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light w-100 text-start">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
      </form>
      <small class="d-block mt-3 text-white-50">PatientCare © {{ date('Y') }}</small>
      <sup class="text-white-50">V1.0.0</sup>
    </div>

  </aside>

  {{-- Main Content --}}
  <main class="flex-grow-1 p-4">
    @yield('content')
  </main>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
