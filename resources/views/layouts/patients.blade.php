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
    html, body {
      height: 100%;
      margin: 0;
    }
    .sidebar {
      position: fixed;
      top: 0; bottom: 0; left: 0;
      width: 240px;
      display: flex;
      flex-direction: column;
      background-color: #00529A;
      padding: 1rem;
      overflow-y: auto;
    }
    main {
      margin-left: 240px;
      height: 100%;
      overflow-y: auto;
      padding: 1.5rem;
    }
    .logo { width: 80px; }
    .avatar {
      width: 100px; height: 100px;
      object-fit: cover;
      border: 3px solid #fff;
      border-radius: 50%;
      margin-bottom: .5rem;
    }
    .nav-link {
      transition: background-color .2s;
      border-radius: .375rem;
      color: #fff;
    }
    .nav-link:hover {
      background-color: rgba(255,255,255,0.2);
      color: #fff !important;
    }
    .nav-link.active {
      background-color: #fff;
      color: #00529A !important;
    }
    .icon { width: 30px; }
  </style>
</head>
<body>
@php
  $user    = Auth::user();
  $patient = $user->patient;
  $unread  = $user->unreadNotifications->count();
@endphp

<aside class="sidebar text-white">
  <div class="text-center mb-4">
    <img src="{{ asset('images/patientcare-logo-white.png') }}"
         alt="PatientCare Logo"
         class="logo img-fluid">
  </div>

  <div class="text-center mb-5">
    @if($patient && $patient->profile_photo)
      <img src="{{ asset('storage/patient/images/'.$patient->profile_photo) }}"
           class="avatar d-block mx-auto" alt="Avatar">
    @else
      <div class="avatar bg-light d-block mx-auto"></div>
    @endif
    <div class="fw-bold">{{ $user->username }}</div>
    @if($patient)
      <div class="small">{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</div>
      <div class="small">ID: {{ str_pad($patient->patient_id,8,'0',STR_PAD_LEFT) }}</div>
    @endif
  </div>

  <nav class="nav flex-column mb-auto">
    <a href="{{ route('patient.dashboard') }}"
       class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
      <i class="fas fa-home fa-lg icon"></i>
      <span class="ms-2">Home</span>
    </a>
    <a href="{{ route('patient.account') }}"
       class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('patient.account') ? 'active' : '' }}">
      <i class="fas fa-user-circle fa-lg icon"></i>
      <span class="ms-2">My Account</span>
    </a>
    <a href="{{ route('patient.billing') }}"
       class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('patient.billing*') ? 'active' : '' }}">
      <i class="fa-solid fa-file-invoice-dollar fa-lg icon"></i>
      <span class="ms-2">Billing</span>
    </a>
    <a href="{{ route('patient.notification') }}"
       class="nav-link d-flex align-items-center position-relative mb-2 {{ request()->routeIs('patient.notification') ? 'active' : '' }}">
      <i class="fa-solid fa-bell fa-lg icon"></i>
      <span class="ms-2">Notifications</span>
      @if($unread)
        <span class="badge bg-danger position-absolute top-0 end-0 translate-middle">{{ $unread }}</span>
      @endif
    </a>
  </nav>

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

<main>
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
