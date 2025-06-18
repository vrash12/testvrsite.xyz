{{-- resources/views/layouts/admission.blade.php --}}

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admission Panel • PatientCare</title>

      {{-- Bootstrap 5 --}}
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      {{-- Font Awesome --}}
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
      {{-- CSS --}}
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>

    {{-- Page --}}
    <div class="d-flex">

      {{-- Sidebar --}}
      <aside class="sidebar bg-primary text-white p-3 vh-100">

          {{-- Top --}}
          <div class="text-center mb-4">
              <img src="{{ asset('images/patientcare-logo-white.png') }}" class="logo img-fluid mt-2 mb-4" alt="Logo">
              <div class="avatar rounded-circle mx-auto mb-2"></div>
              <strong>{{ Auth::user()->username ?? 'Admission User' }}</strong><br>
              <small>Admissioner ID: {{ Auth::id() }}</small>
          </div>

          {{-- List Routes --}}
          <nav class="nav flex-column sidebar-nav ms-2 mt-4">
              <a href="{{ route('admission.dashboard') }}"
                 class="nav-link d-flex align-items-center text-white 
                       {{ request()->routeIs('admission.dashboard') ? 'active' : '' }}">
                  <i class="fas fa-home fa-xl me-3"></i>
                  <span>Home</span>
              </a>

              <a href="{{ route('admission.patients.index') }}"
                 class="nav-link d-flex align-items-center text-white 
                       {{ request()->routeIs('admission.patients.*') ? 'active' : '' }}">
                  <i class="fas fa-users fa-xl me-3"></i>
                  <span>Patients</span>
              </a>

              <a href="{{ route('admission.patients.create') }}"
                 class="nav-link d-flex align-items-center text-white 
                       {{ request()->routeIs('admission.patients.create') ? 'active' : '' }}">
                  <i class="fas fa-user-plus fa-xl me-3"></i>
                  <span>Add Patient</span>
              </a>
          </nav>

          {{-- Bottom --}}
          <div class="footer text-center mt-auto pt-4">
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-light w-100 text-start p-2">
                      <i class="fas fa-sign-out-alt fa-xl me-2"></i> Logout
                  </button>
              </form>
              <small class="d-block mt-3">PatientCare © {{ date('Y') }}</small>
              <sup>V1.0.0</sup>
          </div>

      </aside>

      {{-- Content Placeholder --}}
      <main class="flex-grow-1 p-4">
          @yield('content')
      </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
