{{-- resources/views/layouts/doctor.blade.php --}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Doctor Panel • PatientCare</title>

        {{-- Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        {{-- Font Awesome --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        {{-- CSS --}}
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        
    </head>
    <body>

    <div class="d-flex">

        {{-- Sidebar --}}
        <aside class="sidebar bg-primary text-white p-3 vh-100 flex-shrink-0">

            {{-- Top --}}
            <div class="text-center mb-6">
                <img src="{{ asset('images/patientcare-logo-white.png') }}" alt="Logo" class="logo img-fluid mt-2 mb-4">
                <div class="avatar rounded-circle mx-auto mb-2"></div>
                <strong>{{ Auth::user()->username ?? 'Physician User' }}</strong><br>
                <small>Physician ID: {{ Auth::id() }}</small>
            </div>

            {{-- Navigation --}}
            <nav class="mb-auto ms-2 mt-4">

                <a href="{{ route('') }}" class="nav-link d-flex align-items-center text-white">
                    <i class="fas fa-home fa-xl me-3"></i>
                    <span>Home</span>
                </a>

                <a href="{{ route('') }}" class="nav-link d-flex align-items-center text-white">
                    <i class="fas fa-prescription fa-xl me-3"></i>
                    <span>Prescribe</span>
                </a>

            </nav>

            {{-- Footer --}}
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

        {{-- Main Content --}}
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
