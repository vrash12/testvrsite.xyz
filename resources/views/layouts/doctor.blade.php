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
    @vite('resources/css/app.css')

</head>
<body>
    <aside class="sidebar">
        <div class="top-part">
            <img src="{{ asset('images/patientcare-logo-white.png') }}" alt="Logo" class="logo">
            <div class="avatar"></div>
            <div class="name" >{{ Auth::user()->username ?? "Physician User"}}</div>
            <div class="id-label">Physician ID: {{Auth::id()}}</div>
        </div>

        <nav>
            <ul>
                <li>
                    <a href="{{ route('doctor.dashboard') }}">
                        <i class="fas fa-home"></i>Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.prescribe') }}">
                        <i class="fa-solid fa-prescription"></i>Prescribe
                    </a>
                </li>
                <li>
                    <form action="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn text-white w-100 text-start" style="background:none; border:none">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <div class="footer">
             PatientCare © {{ date('Y') }}<br>
             Version 1.0.0
        </div>

    </aside>
</body>
</html>