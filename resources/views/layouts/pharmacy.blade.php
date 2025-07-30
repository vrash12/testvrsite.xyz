{{-- resources/views/layouts/pharmacy.blade.php --}}

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Pharmacy • PatientCare</title>

      {{-- Bootstrap 5 --}}
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      {{-- Font Awesome --}}
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
      {{-- CSS --}}
        <style>
           body, html {
        height: 100%;
        margin: 0;
      }
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 240px;
        display: flex;
        flex-direction: column;
        background-color: #00529A;
        overflow-y: auto; /* if your sidebar grows */
      }
      main {
        margin-left: 240px; /* make room for sidebar */
        height: 100vh;
        overflow-y: auto;   /* only main scrolls */
        padding: 1.5rem;
      }
            .logo{ width:80px } 
            .avatar { width:90px; height: 90px; background-color: aliceblue; }
            .nav-link {transition: background-color 0.2s ease; border-radius: 0.375rem;}
            .nav-link:hover { background-color: rgba(255, 255, 255, 0.2); color: #fff !important; }
            nav .icon{ width: 30px;}
            .hdng{font-size: 1.5em;}
      </style>
  </head>

  <body>

    {{-- Page --}}
    <div class="d-flex">

      {{-- Sidebar --}}
    <aside class="sidebar text-white p-3">

          {{-- Top --}}
          <div class="text-center mb-6">
              <img src="{{ asset('images/patientcare-logo-white.png') }}" class="logo img-fluid mt-2 mb-4" alt="Logo">
              <div class="avatar rounded-circle mx-auto mb-2"></div>
              <strong>{{ Auth::user()->username ?? 'Pharmacist User' }}</strong><br>
              <small>Pharmacist ID: {{ Auth::id() }}</small>
          </div>

          {{-- List Routes --}}
          <nav class="mb-auto ms-2 mt-4">

              <a href="{{ route('pharmacy.dashboard') }}"
                 class="nav-link d-flex text-white gap-2 px-2 py-2 
                 {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
                  <span class="icon justify-content-center align-items-center">
                        <i class="fas fa-home fa-xl"></i>
                  </span>
                  <span class="ms-2">Home</span>
              </a>


                     <a href="{{ route('pharmacy.medicines.index') }}"
                 class="nav-link d-flex text-white gap-2 px-2 py-2
                 {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
                  <span class="icon justify-content-center align-items-center">
                        <i class="fas fa-medkit fa-xl"></i>
                  </span>

                  <span class="ms-2">Medicine</span>
              </a>

              <a href="{{ route('pharmacy.charges.index') }}"
                 class="nav-link d-flex text-white gap-2 px-2 py-2
                 {{ request()->routeIs('pharmacy.medicine.create') ? 'active' : '' }}">
                  <span class="">
                        <i class="fas fa-pills fa-xl"></i>
                  </span>
                  <span class="ms-2">New Med Charge</span>
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
        @stack('scripts')
  </body>
</html>
