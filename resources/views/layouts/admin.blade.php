{{--resources/views/layouts/admin.blade.php--}}

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Admin • PatientCare</title>

      {{-- Bootstrap 5 --}}
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      {{-- Font Awesome --}}
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
      {{-- CCS --}}
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>

      {{-- Page --}}
      <div class="d-flex">

          {{-- Sidebar --}}
          <aside class="sidebar bg-primary text-white p-3 vh-100 ">

              {{-- Top --}}
              <div class="text-center mb-6">
                  <img src="{{ asset('images/patientcare-logo-white.png') }}" class="logo img-fluid mt-2 mb-4" alt="logo">
                  <div class="avatar rounded-circle mx-auto mb-2 "></div>
                  <strong>{{ Auth::user()-> username ?? 'Admin User' }}</strong><br>
                  <small>Admin ID: {{ Auth::id() }}</small>
              </div>

              {{-- List Routes --}}
              <nav class="nav flex-column mb-auto sidebar-nav ms-2 mt-4">
                 <a href="{{route('admin.dashboard')}}" class="nav-link d-flex align-items-center text-white 
                          {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                   <i class="fa-solid fa-gauge fa-xl me-3"></i>
                   <span>Dashboard</span>
                  </a>

                  <a href="{{route('admin.users.index')}}" class="nav-link d-flex align-items-center text-white 
                          {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                   <i class="fas fa-users fa-xl me-3"></i>
                   <span>Manage Users</span>
                  </a>
                  <div class="mt-4 px-2 text-uppercase text-white-50 small">Manage Resources</div>

<a href="{{ route('admin.resources.index') }}"
   class="nav-link d-flex align-items-center text-white 
          {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
  <i class="fas fa-layer-group fa-xl me-3"></i>
  <span>Rooms &amp; Beds</span>
</a>
               </nav>

              {{-- Bottom --}}
              <div class="footer text-center">
                  <div class="mt-auto text-center small pt-3 mb-4">
                      <form method="POST" action="{{ route('logout') }}">
                         @csrf
                          <button type="submit" class="btn btn-sm btn-outline-light w-100 text-start p-2">
                              <i class="fas fa-sign-out-alt fa-xl me-2"></i> Logout
                          </button>
                      </form>
                   </div>
                  <small class="mt-">PatientCare © {{ date('Y') }}</small>
                  <suP>V1.0.0</suP>
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
 