{{-- resources/views/layouts/doctor.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Panel • PatientCare</title>

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
      overflow-y: auto;
      padding: 1rem;
    }
    main {
      margin-left: 240px;
      height: 100vh;
      overflow-y: auto;
      padding: 1.5rem;
    }
    .logo { width: 80px; }
    .avatar {
      width: 90px; height: 90px;
      background-color: aliceblue;
      margin: 0 auto 1rem;
      border-radius: 50%;
    }
    .nav-link {
      color: #fff;
      transition: background-color 0.2s ease;
      border-radius: 0.375rem;
    }
    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .footer {
      margin-top: auto;
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  <aside class="sidebar text-white">
    <div class="text-center mb-4">
      <img src="{{ asset('images/patientcare-logo-white.png') }}" class="logo img-fluid mb-3" alt="Logo">
      <div class="avatar"></div>
      <strong>{{ Auth::user()->username ?? 'Physician User' }}</strong><br>
      <small>Physician ID: {{ Auth::id() }}</small>
    </div>

    {{-- Navigation --}}
    <nav class="nav flex-column mb-4">
      <a href="{{ route('doctor.dashboard') }}"
         class="nav-link d-flex align-items-center mb-2">
        <i class="fas fa-home fa-lg me-2"></i>
        Home
      </a>
      <a href="{{ route('doctor.orders.index') }}"
         class="nav-link d-flex align-items-center mb-2">
        <i class="fas fa-clipboard-list fa-lg me-2"></i>
        Orders
      </a>
      {{-- Add more links here as needed --}}
    </nav>

    {{-- Footer --}}
    <div class="footer text-center">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="btn btn-sm btn-outline-light w-100 text-start mb-3">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
      </form>
      <small>PatientCare © {{ date('Y') }}</small><br>
      <sup>V1.0.0</sup>
    </div>
  </aside>

  {{-- Main Content --}}
  <main>
    @yield('content')
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
