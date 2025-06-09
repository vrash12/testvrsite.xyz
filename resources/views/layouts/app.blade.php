{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PatientCare Portal</title>

    {{-- Bootstrap CSS (CDN or compiled) --}}
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    {{-- Optional: your custom CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- Font Awesome (if needed) --}}
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    >
</head>
<body>
    {{-- ===== Navbar ===== --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="PatientCare Logo" style="height: 30px; margin-right: 8px;">
                <span class="fw-bold">PatientCare</span>
            </a>
            <button
              class="navbar-toggler"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        @guest
                            <a class="btn btn-primary ms-3" href="{{ route('login') }}">LOGIN</a>
                        @else
                            <a class="btn btn-outline-secondary ms-3" href="{{ route('dashboard') }}">
                                <i class="fa fa-user"></i> Dashboard
                            </a>
                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ===== Page Content ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap JS (and Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Optional: your custom JS --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
