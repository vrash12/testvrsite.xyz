<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Billing Panel • PatientCare</title>

      {{-- Bootstrap 5 --}}
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      {{-- Font Awesome --}}
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

      <style>
          body { overflow-x: hidden; }
          .sidebar {
            width: 240px;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 1;
            display: flex;
            flex-direction: column;
            background-color: #00529A;
          }
          .logo { width: 80px; }
          .avatar {
            width: 90px; height: 90px;
            background-color: aliceblue;
          }
          .nav-link {
            transition: background-color 0.2s ease;
            border-radius: .375rem;
          }
          .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: #fff !important;
          }
          .nav-link.active {
            background-color: rgba(255,255,255,0.3) !important;
            color: #fff !important;
          }
          .icon { width: 30px; }
          .hdng {
            font-size: 1.5em;
            color: #00529A;
            font-weight: bold;
          }
      </style>
  </head>
  <body>

    <div class="d-flex">

      {{-- Sidebar --}}
      <aside class="sidebar text-white p-3 vh-100 flex-shrink-0">

        {{-- Top --}}
        <div class="text-center mb-6">
            <img src="{{ asset('images/patientcare-logo-white.png') }}"
                 class="logo img-fluid mt-2 mb-4"
                 alt="Logo">
            <div class="avatar rounded-circle mx-auto mb-2"></div>
            <strong>{{ Auth::user()->username ?? 'Billing User' }}</strong><br>
            <small>Billing ID: {{ Auth::id() }}</small>
        </div>

        {{-- Navigation --}}
        <nav class="mb-auto ms-2 mt-4">
            <a href="{{ route('billing.dashboard') }}"
               class="nav-link d-flex text-white px-2 py-2 gap-2
                      {{ request()->routeIs('billing.dashboard') ? 'active' : '' }}">
              <span class="icon d-flex justify-content-center align-items-center">
                <i class="fas fa-home fa-xl"></i>
              </span>
              <span class="ms-2">Home</span>
            </a>

   

            <a href="{{ route('billing.discharge.index') }}"
               class="nav-link d-flex text-white px-2 py-2 gap-2
                      {{ request()->routeIs('billing.discharge.*') ? 'active' : '' }}">
              <span class="icon d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-lock-open fa-xl"></i>
              </span>
              <span class="ms-2">Finish Patients</span>
            </a>

            <a href="{{ route('billing.charges.index') }}"
               class="nav-link d-flex text-white px-2 py-2 gap-2
                      {{ request()->routeIs('billing.charges.*') ? 'active' : '' }}">
              <span class="icon d-flex justify-content-center align-items-center">
                <i class="fas fa-hand-holding-usd fa-xl"></i>
              </span>
              <span class="ms-2">Manual Charges</span>
            </a>

            <a href="{{ route('billing.dispute.queue') }}"
               class="nav-link d-flex text-white px-2 py-2 gap-2
                      {{ request()->routeIs('billing.dispute.*') ? 'active' : '' }}">
              <span class="icon d-flex justify-content-center align-items-center">
                <i class="fas fa-ticket-alt fa-xl"></i>
              </span>
              <span class="ms-2">Billing Disputes</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="footer text-center mt-auto pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="btn btn-sm btn-outline-light w-100 text-start p-2">
                  <i class="fas fa-sign-out-alt fa-xl me-2"></i> Logout
                </button>
            </form>
            <small class="d-block mt-3">PatientCare © {{ date('Y') }}</small>
            <sup>V1.0.0</sup>
        </div>

      </aside>

      {{-- Main Content --}}
      <main class="p-4" style="margin-left: 240px;">
          @yield('content')
      </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
  </body>
</html>
