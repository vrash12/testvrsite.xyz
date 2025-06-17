{{-- resources/views/layouts/patients.blade.php --}}

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Patient Panel • PatientCare</title>
        {{-- Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        {{-- Font Awesome --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        {{-- CSS --}}
        @vite('resources/css/app.css')
    </head>
    <body>
        <aside class="sidebar">
            <div class="top-section">
                <img src="{{ asset('images/patientcare-logo-white.png') }}" alt="logo" class="logo">
                <div class="avatar"></div>
                <div class="name">{{ Auth::user()->username ?? 'patient'}}</div>
                <div class="id-label">Patient ID:{{ Auth::id() }}</div>
            </div>

            <nav>
                <li>
                    <a href="{{ route('') }}">
                        <i class="fas fa-home"></i>Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('') }}">
                        <i class="fa-solid fa-circle-user"></i>My Account
                    </a>
                </li>
                <li>
                    <a href="{{ route('') }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i>Billing
                    </a>
                </li>
                <li>
                    <a href="{{ route('') }}">
                        <i class="fa-solid fa-bell"></i>Notifications
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        <button type="submit" class="btn text-white w-100 text-start" style="background:none; border:none;">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </button>
                    </form>
                </li>
            </nav>
            <div class="footer">
            PatientCare © {{ date('Y') }} <br>
            Version 1.0.0
            </div>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>

        {{-- Bootstrap JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>