{{-- resources/views/layouts/billing.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing Panel • PatientCare</title>
</head>
<body>
    <aside class="sidebar">
        <div class="top-part">
            <img src=" {{ assset('images/patientcare-logo-white.png') }}" alt="Logo">
            <div class="avatar"></div>
            <div class="name">{{ Auth::user()->username ?? 'Billing Staff'}}</div>
            <div class="id-label">{{ Auth::id() }}</div>
        </div>
        <nav>
            <li>
                <a href="{{ route('') }}"></a>
            </li>
            <li>
                <a href="{{ route('') }}"></a>
            </li>
            <li>
                <a href="{{ route('') }}"></a>
            </li>
            <li>
                <a href="{{ route('') }}"></a>
            </li>
        </nav>
        <div class="footer">
            PatientCare © {{ date('Y') }}<br>
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